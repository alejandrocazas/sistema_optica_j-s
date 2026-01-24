<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Payment;
use App\Models\CashRegister;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    // Vista del POS (Punto de Venta Livewire)
    public function create() {
        return view('sales.pos');
    }

    // Listado de Ventas
    public function index() {
        $sales = Sale::with(['patient', 'user'])
                     ->latest()
                     ->paginate(20);

        // Verificar si hay caja abierta (para alertas visuales)
        $hasOpenRegister = CashRegister::where('user_id', auth()->id())
                                       ->where('status', 'abierta')
                                       ->exists();

        return view('sales.index', compact('sales', 'hasOpenRegister'));
    }

    // Guardar Venta (Backend/API)
    // NOTA: El POS usa Livewire (PosComponent), este método es para ventas manuales/externas
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {

            $branchId = auth()->user()->branch_id ?? 1;

            // Calculamos totales iniciales
            $totalBruto = 0;
            $itemsData = [];

            // Validar Stock primero
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                $stockEnSucursal = DB::table('branch_product')
                    ->where('branch_id', $branchId)
                    ->where('product_id', $product->id)
                    ->value('stock');

                if (is_null($stockEnSucursal) || $stockEnSucursal < $item['quantity']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'items' => "Stock insuficiente en sucursal para: {$product->name}"
                    ]);
                }

                $subtotal = $product->price_sell * $item['quantity']; // Precio de venta
                $totalBruto += $subtotal;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price_sell,
                    'subtotal' => $subtotal
                ];
            }

            // Aplicar Descuento
            $discount = $request->input('discount', 0);
            $totalNeto = max(0, $totalBruto - $discount);
            $paidAmount = $request->input('payment_amount', 0);

            // A. Crear la Venta
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'patient_id' => $request->patient_id,
                'branch_id' => $branchId,
                'receipt_number' => 'REC-' . time(),
                'status' => 'laboratorio',

                // Nuevos campos
                'has_consultation' => $request->boolean('has_consultation'),
                'delivery_date' => $request->input('delivery_date'),
                'observations' => $request->input('observations'),

                // Totales
                'discount' => $discount,
                'total' => $totalNeto,
                'paid_amount' => $paidAmount,
                'balance' => max(0, $totalNeto - $paidAmount),
                'payment_status' => ($paidAmount >= $totalNeto) ? 'pagado' : 'parcial',
            ]);

            // B. Guardar Detalles y Restar Stock
            foreach ($itemsData as $data) {
                $sale->details()->create([
                    'product_id' => $data['product']->id,
                    'quantity' => $data['quantity'],
                    'price' => $data['price'],
                    'subtotal' => $data['subtotal'],
                ]);

                // Restar en Sucursal
                DB::table('branch_product')
                    ->where('branch_id', $branchId)
                    ->where('product_id', $data['product']->id)
                    ->decrement('stock', $data['quantity']);

                // Restar Global
                $data['product']->decrement('stock', $data['quantity']);
            }

            // C. Registrar Pago
            if ($paidAmount > 0) {
                Payment::create([
                    'sale_id' => $sale->id,
                    'amount' => $paidAmount,
                    'method' => $request->input('payment_method', 'Efectivo'),
                    'created_at' => now()
                ]);
            }

            return redirect()->route('sales.index')->with('success', 'Venta registrada correctamente.');
        });
    }

    // --- IMPRIMIR RECIBO (PDF) ---
    public function print($id)
    {
        $sale = Sale::with(['details.product', 'patient', 'user', 'branch'])->findOrFail($id);

        // 1. Generar QR
        $qrData = "RECIBO: {$sale->receipt_number} | TOTAL: {$sale->total} | FECHA: {$sale->created_at->format('d/m/Y')}";
        $qrImage = base64_encode(QrCode::format('svg')->size(100)->generate($qrData));

        // 2. Lógica del LOGO (Marca de Agua) - ¡ESTO FALTABA!
        $path = public_path('images/grupo.jpg'); // Asegúrate que la imagen exista
        $logoBase64 = null;

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // 3. Generar PDF
        // Pasamos tanto $qrImage como $logoBase64 a la vista
        $pdf = Pdf::loadView('sales.receipt', compact('sale', 'qrImage', 'logoBase64'));

        // Ajuste para ticket térmico (80mm ancho aprox)
        $pdf->setPaper([0, 0, 226.77, 800], 'portrait');

        return $pdf->stream('ticket-'.$sale->receipt_number.'.pdf');
    }

    // --- ANULAR VENTA ---
    public function destroy(Request $request, $id)
    {
        // 1. Validar permiso (Solo Admin)
        if(auth()->user()->role !== 'admin') {
            return back()->with('error', 'No tienes permisos para anular ventas.');
        }

        $sale = Sale::with('details')->findOrFail($id);

        // 2. Bloqueo de seguridad
        if($sale->status === 'entregado') {
             return back()->with('error', 'No se puede eliminar una venta entregada.');
        }

        // 3. Justificación obligatoria
        if(!$request->input('reason')) {
            return back()->with('error', 'Es obligatorio indicar el motivo de la anulación.');
        }

        DB::transaction(function () use ($sale, $request) {

            // A. RESTAURAR STOCK
            foreach($sale->details as $detail) {
                // Restaurar en Sucursal
                DB::table('branch_product')
                    ->where('branch_id', $sale->branch_id)
                    ->where('product_id', $detail->product_id)
                    ->increment('stock', $detail->quantity);

                // Restaurar Global
                $product = Product::find($detail->product_id);
                if($product) {
                    $product->increment('stock', $detail->quantity);
                }
            }

            // B. DEVOLVER DINERO (Egreso de Caja)
            if ($sale->paid_amount > 0) {
                $cajaAbierta = CashRegister::where('user_id', auth()->id())
                                ->where('status', 'abierta')
                                ->first();

                if ($cajaAbierta) {
                    Expense::create([
                        'cash_register_id' => $cajaAbierta->id,
                        'user_id' => auth()->id(),
                        'amount' => $sale->paid_amount,
                        'description' => 'DEVOLUCIÓN: Anulación Venta ' . $sale->receipt_number . '. Motivo: ' . $request->input('reason')
                    ]);
                }
            }

            // C. Auditoría y Borrado Lógico
            $sale->update([
                'deletion_reason' => $request->input('reason'),
                'deleted_by' => auth()->id(),
                'status' => 'cancelado'
            ]);

            $sale->delete(); // Soft Delete
        });

        return back()->with('success', 'Venta anulada correctamente.');
    }

    // Actualizar Fecha de Entrega
    public function updateDate(Request $request, Sale $sale)
    {
        $sale->update(['delivery_date' => $request->delivery_date]);
        return back()->with('success', 'Fecha de entrega actualizada.');
    }

    // Actualizar Observaciones
    public function updateObservations(Request $request, Sale $sale)
    {
        $sale->update(['observations' => $request->observations]);
        return back()->with('success', 'Observaciones guardadas.');
    }
}
