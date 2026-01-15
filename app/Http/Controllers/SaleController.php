<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Sale;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use App\Models\Product;

class SaleController extends Controller
{
    public function create() {
    return view('sales.pos');
}

// En SaleController.php

public function store(Request $request)
{
    // 1. Validación básica
    $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
        
        // Obtener la sucursal del usuario actual (o la 1 por defecto)
        $branchId = auth()->user()->branch_id ?? 1;

        // A. Crear la Venta
        $sale = Sale::create([
            'user_id' => auth()->id(),
            'patient_id' => $request->patient_id,
            'branch_id' => $branchId, // <--- Importante: Usar la variable
            'receipt_number' => 'REC-' . time(),
            'status' => 'laboratorio',
            'payment_status' => 'pendiente',
            'total' => 0,
            'balance' => 0,
            'paid_amount' => 0,
            'date' => now(),
        ]);

        $totalVenta = 0;

        // B. Recorrer productos
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            
            // === CORRECCIÓN DE STOCK (Multi-Sucursal) ===
            
            // 1. Buscar el stock en la tabla pivote de ESTA sucursal
            $stockEnSucursal = \Illuminate\Support\Facades\DB::table('branch_product')
                ->where('branch_id', $branchId)
                ->where('product_id', $product->id)
                ->value('stock');

            // Si no existe el registro en la sucursal, asumimos stock 0
            if (is_null($stockEnSucursal) || $stockEnSucursal < $item['quantity']) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'items' => "Stock insuficiente en esta sucursal para: {$product->name}"
                ]);
            }

            // 2. RESTAR STOCK EN LA SUCURSAL (Tabla branch_product)
            \Illuminate\Support\Facades\DB::table('branch_product')
                ->where('branch_id', $branchId)
                ->where('product_id', $product->id)
                ->decrement('stock', $item['quantity']);
            
            // (Opcional) Si quieres mantener un stock global referencial:
            // $product->decrement('stock', $item['quantity']); 

            // =============================================

            // 3. Guardar detalle
            $sale->details()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $product->price * $item['quantity'],
            ]);

            $totalVenta += ($product->price * $item['quantity']);
        }

        // C. Actualizar Totales y Pagos (Igual que antes)
        $sale->total = $totalVenta;
        $anticipo = $request->input('payment_amount', 0);
        
        if ($anticipo > 0) {
            \App\Models\Payment::create([
                'sale_id' => $sale->id,
                'amount' => $anticipo,
                'method' => $request->input('payment_method', 'efectivo'),
                'created_at' => now()
            ]);
            $sale->paid_amount = $anticipo;
            $sale->payment_status = ($anticipo >= $totalVenta) ? 'pagado' : 'parcial';
        } else {
            $sale->payment_status = 'pendiente';
        }

        $sale->balance = $totalVenta - $sale->paid_amount;
        $sale->save();

        return redirect()->route('sales.index')->with('success', 'Venta registrada y stock de sucursal actualizado.');
    });
}

public function index() {
    // Cambiamos get() por paginate(20)
    $sales = \App\Models\Sale::with(['patient', 'user']) // Trae datos de paciente y vendedor
                         ->latest()                      // Ordena por fecha (más nuevo primero)
                         ->paginate(20);                 // Muestra 20 por página

    
    // ===> AGREGAR ESTO <===
    // Verificar si el usuario tiene una caja abierta actualmente
    $hasOpenRegister = \App\Models\CashRegister::where('user_id', auth()->id())
                        ->where('status', 'abierta')
                        ->exists();

    return view('sales.index', compact('sales', 'hasOpenRegister'));
}

public function print($id)
{
    
    $sale = Sale::with(['details.product', 'patient', 'user', 'branch'])->findOrFail($id);

    // Generamos el QR con la URL pública de la venta o datos clave
    // Ejemplo: "Venta #123 | Total: 500 | Fecha: ..."
    $qrData = "RECIBO: {$sale->receipt_number} | TOTAL: {$sale->total}";

    // Convertimos a imagen Base64 para que DomPDF lo entienda
    $qrImage = base64_encode(QrCode::format('svg')->size(100)->generate($qrData));

    $pdf = Pdf::loadView('sales.receipt', compact('sale', 'qrImage')); // Pasamos la variable
    $pdf->setPaper([0, 0, 226.77, 1000], 'portrait');

    return $pdf->stream('ticket-'.$sale->receipt_number.'.pdf');
}

    
    // CORRECCIÓN 1: Agregamos "Request $request" aquí 👇
    public function destroy(Request $request, $id)
    {
        // 1. Validar permiso (Solo Admin)
        if(auth()->user()->role !== 'admin') {
            return back()->with('error', 'No tienes permisos para anular ventas.');
        }

        $sale = Sale::with('details')->findOrFail($id);

        // 2. Bloqueo de seguridad
        if($sale->status === 'entregado') {
             return back()->with('error', 'No se puede eliminar una venta que ya fue entregada al cliente.');
        }

        // 3. Validar que venga la justificación
        if(!$request->input('reason')) {
            return back()->with('error', 'Es obligatorio indicar el motivo de la anulación.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($sale, $request) {
            
            // A. RESTAURAR STOCK (En Sucursal y Global)
            foreach($sale->details as $detail) {
                
                // CORRECCIÓN 2: Restaurar en la Sucursal específica (Tabla Pivote)
                \Illuminate\Support\Facades\DB::table('branch_product')
                    ->where('branch_id', $sale->branch_id) // Usamos la sucursal original de la venta
                    ->where('product_id', $detail->product_id)
                    ->increment('stock', $detail->quantity);

                // Restaurar Global (Opcional, para mantener sincronía)
                $product = Product::find($detail->product_id);
                if($product) {
                    $product->increment('stock', $detail->quantity);
                }
            }

            // B. CORRECCIÓN 3: DEVOLVER DINERO (Crear Egreso de Caja)
            if ($sale->paid_amount > 0) {
                // Buscamos si el admin tiene caja abierta para registrar la salida
                $cajaAbierta = \App\Models\CashRegister::where('user_id', auth()->id())
                                ->where('status', 'abierta')
                                ->first();
                
                if ($cajaAbierta) {
                    \App\Models\Expense::create([
                        'cash_register_id' => $cajaAbierta->id,
                        'user_id' => auth()->id(),
                        'amount' => $sale->paid_amount, // El monto que se devuelve
                        'description' => 'DEVOLUCIÓN: Anulación Venta ' . $sale->receipt_number
                    ]);
                }
            }

            // C. Guardar auditoría y borrar
            $sale->deletion_reason = $request->input('reason');
            $sale->deleted_by = auth()->id();
            $sale->status = 'cancelado'; 
            $sale->save();

            $sale->delete(); // Soft Delete
        });

        return back()->with('success', 'Venta anulada. Stock restaurado y devolución registrada en caja.');
    }

    public function updateDate(Request $request, Sale $sale)
{
    $sale->update(['delivery_date' => $request->delivery_date]);
    return back()->with('success', 'Fecha de entrega actualizada.');
}

public function updateObservations(Request $request, Sale $sale)
{
    $sale->update(['observations' => $request->observations]);
    return back()->with('success', 'Observaciones guardadas.');
}
}
