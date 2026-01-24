<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Patient;
use App\Models\Sale;
use App\Models\Prescription;
use App\Models\SaleDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PosComponent extends Component
{
    // Variables para Receta Externa
    public $od_esfera, $od_cilindro, $od_eje, $add_od;
    public $oi_esfera, $oi_cilindro, $oi_eje, $add_oi;
    public $dip, $diagnostico, $observaciones_receta;

    // Buscadores
    public $searchProduct = '';
    public $searchPatient = '';

    // Datos de la Venta
    public $cart = [];
    public $patient_id = null;
    public $selectedPatientName = null;
    public $withConsultation = false; // Check de Consulta

    // Pago
    public $paymentMethod = 'Efectivo';
    public $amountPaid = '';

    // Total
    public $total = 0;
    public $discount = 0; // Variable del Descuento
    public $delivery_date;
    public $observations;
    public $paymentReference = '';

    protected $listeners = ['confirmSale' => 'saveSale'];

    public function mount()
    {
        $this->cart = [];
        $this->amountPaid = '';
    }

    // --- CARRITO ---
    public function addToCart($productId)
    {
        $product = \App\Models\Product::find($productId);

        if(!$product) return;

        $stockDisponible = $product->stock_actual;

        if($stockDisponible <= 0) {
            $this->dispatch('error', 'Producto agotado en esta sucursal.');
            return;
        }

        if(isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] + 1 > $stockDisponible) {
                $this->dispatch('error', 'No hay suficiente stock. Solo quedan ' . $stockDisponible . ' unidades.');
                return;
            }

            $this->cart[$productId]['quantity']++;
            $this->cart[$productId]['subtotal'] = $this->cart[$productId]['quantity'] * $this->cart[$productId]['price'];

        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price_sell,
                'quantity' => 1,
                'subtotal' => $product->price_sell,
                'code' => $product->code
            ];
        }

        $this->calculateTotal();
    }

    public function decreaseQuantity($productId)
    {
        if(isset($this->cart[$productId])) {
            if($this->cart[$productId]['quantity'] > 1) {
                $this->cart[$productId]['quantity']--;
                $this->cart[$productId]['subtotal'] = $this->cart[$productId]['quantity'] * $this->cart[$productId]['price'];
                $this->calculateTotal();
            }
        }
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = array_sum(array_column($this->cart, 'subtotal'));
    }

    // --- CLIENTE ---
    public function selectPatient($id, $name)
    {
        $this->patient_id = $id;
        $this->selectedPatientName = $name;
        $this->searchPatient = '';
    }

    // --- GUARDAR VENTA (AQUÍ ESTÁ LA CORRECCIÓN) ---
    public function saveSale()
    {
        if(empty($this->cart)) return;
        if(!$this->patient_id) return;

        if ($this->paymentMethod == 'QR' && empty($this->paymentReference)) {
            $this->dispatch('sale-error', ['message' => 'Debe ingresar el Nro de Referencia del QR.']);
            return;
        }

        DB::transaction(function () {
            // 0. Obtener Sucursal Actual
            $branchId = auth()->user()->branch_id;

            // 1. Calcular Totales Reales
            $montoPagado = (float)$this->amountPaid;
            $montoDescuento = (float)$this->discount;

            // Total Final = Total Carrito - Descuento
            $totalConDescuento = max(0, $this->total - $montoDescuento);

            // 2. Crear Venta
            $sale = Sale::create([
                'receipt_number' => 'V-'.time(),
                'user_id' => auth()->id(),
                'branch_id' => $branchId,
                'patient_id' => $this->patient_id,

                // GUARDAMOS EL TOTAL YA CON EL DESCUENTO APLICADO
                'total' => $totalConDescuento,

                // GUARDAMOS EL DESCUENTO (IMPORTANTE PARA EL RECIBO)
                'discount' => $montoDescuento,

                // GUARDAMOS EL CHECK DE CONSULTA
                'has_consultation' => $this->withConsultation,

                'paid_amount' => $montoPagado,

                // EL SALDO SE CALCULA SOBRE EL TOTAL CON DESCUENTO
                'balance' => max(0, $totalConDescuento - $montoPagado),

                'status' => 'laboratorio',
                'payment_status' => ($montoPagado >= $totalConDescuento) ? 'pagado' : 'parcial',
                'delivery_date' => $this->delivery_date ?: null,
                'observations' => $this->observations,
            ]);

            // 3. Detalles y Descuento de Stock
            foreach($this->cart as $item) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal']
                ]);

                // Descontar de la Sucursal
                DB::table('branch_product')
                    ->where('branch_id', $branchId)
                    ->where('product_id', $item['id'])
                    ->decrement('stock', $item['quantity']);

                // Descontar del Global
                Product::find($item['id'])->decrement('stock', $item['quantity']);
            }

            // 4. Pago
            if($montoPagado > 0) {
                Payment::create([
                    'sale_id' => $sale->id,
                    'amount' => $montoPagado,
                    'method' => $this->paymentMethod,
                    'reference' => $this->paymentReference
                ]);
            }

            // 5. Limpiar
            $this->reset(['cart', 'total', 'discount', 'patient_id', 'amountPaid', 'delivery_date', 'observations', 'selectedPatientName', 'paymentReference', 'withConsultation']);

            // 6. Emitir evento
            $this->dispatch('sale-success', ['saleId' => $sale->id]);
        });
    }

    public function render()
    {
        if(strlen($this->searchProduct) > 0) {
            $products = Product::with('category')
                        ->where('name', 'like', '%'.$this->searchProduct.'%')
                        ->orWhere('code', 'like', '%'.$this->searchProduct.'%')
                        ->take(20)->get();
        } else {
            $products = Product::with('category')->latest()->take(20)->get();
        }

        $patients = [];
        if(strlen($this->searchPatient) > 0) {
            $patients = Patient::where('name', 'like', '%'.$this->searchPatient.'%')
                        ->orWhere('ci', 'like', '%'.$this->searchPatient.'%')
                        ->take(5)->get();
        }

        return view('livewire.pos-component', [
            'products' => $products,
            'patients' => $patients
        ]);
    }

    // --- GUARDAR RECETA ---
    public function savePrescription()
    {
        if(!$this->patient_id) return;

        Prescription::create([
            'patient_id' => $this->patient_id,
            'user_id' => auth()->id(),
            'od_esfera' => $this->od_esfera,
            'od_cilindro' => $this->od_cilindro,
            'od_eje' => $this->od_eje,
            'add_od' => $this->add_od,
            'oi_esfera' => $this->oi_esfera,
            'oi_cilindro' => $this->oi_cilindro,
            'oi_eje' => $this->oi_eje,
            'add_oi' => $this->add_oi,
            'dip' => $this->dip,
            'diagnostico' => $this->diagnostico,
            'observaciones' => "RECETA EXTERNA. " . $this->observaciones_receta,
        ]);

        $this->reset(['od_esfera', 'od_cilindro', 'od_eje', 'add_od', 'oi_esfera', 'oi_cilindro', 'oi_eje', 'add_oi', 'dip', 'diagnostico', 'observaciones_receta']);

        $this->dispatch('close-prescription-modal');
        $this->dispatch('simple-alert', ['message' => 'Receta guardada']);
    }
}
