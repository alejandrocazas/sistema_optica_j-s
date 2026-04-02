<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        // Traemos las compras con el usuario y la sucursal para saber a dónde fue
        $purchases = \App\Models\Purchase::with(['user', 'branch'])
                        ->latest()
                        ->paginate(20);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $products = \App\Models\Product::orderBy('name')->get();
        $branches = \App\Models\Branch::all();

        // --- NUEVO: Enviamos las categorías ---
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('purchases.create', compact('products', 'branches', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier' => 'required|string',
            'purchase_date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.cost' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // 1. Crear Cabecera de Compra
                $purchase = \App\Models\Purchase::create([
                    'user_id' => auth()->id(),
                    'branch_id' => $request->branch_id,
                    'supplier' => $request->supplier,
                    'purchase_date' => $request->purchase_date,
                    'total_cost' => 0 // Inicia en 0, se actualiza al final
                ]);

                $totalCompra = 0;

                // 2. Procesar cada producto
                foreach ($request->products as $item) {
                    $subtotal = $item['quantity'] * $item['cost'];
                    $totalCompra += $subtotal;

                    // Guardar detalle
                    \App\Models\PurchaseDetail::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'cost_price' => $item['cost']
                    ]);

                    // ---------------------------------------------------------
                    // 3. ACTUALIZACIÓN DE STOCK (GLOBAL Y SUCURSAL)
                    // ---------------------------------------------------------
                    $product = \App\Models\Product::find($item['id']);

                    // A. Actualizar precio de compra global
                    $product->update(['price_buy' => $item['cost']]);

                    // B. Sumar al stock GLOBAL (Tabla products)
                    $product->increment('stock', $item['quantity']);

                    // C. Actualizar STOCK ESPECÍFICO DE LA SUCURSAL (Tabla branch_product)
                    $branchProduct = $product->branches()->where('branch_id', $request->branch_id)->first();

                    if ($branchProduct) {
                        // SI EXISTE: Sumamos al stock actual de esa sucursal
                        $newStock = $branchProduct->pivot->stock + $item['quantity'];
                        $product->branches()->updateExistingPivot($request->branch_id, [
                            'stock' => $newStock
                        ]);
                    } else {
                        // NO EXISTE: Creamos el registro en esa sucursal
                        $product->branches()->attach($request->branch_id, [
                            'stock' => $item['quantity']
                        ]);
                    }
                }

                // 4. Actualizar el total final de la compra
                $purchase->update(['total_cost' => $totalCompra]);
            });

            return redirect()->route('purchases.index')->with('success', 'Compra registrada y stock actualizado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar compra: ' . $e->getMessage())->withInput();
        }
    }

    // ========================================================
    // MÓDULO DE IMPORTACIÓN DE COMPRAS POR EXCEL (CSV)
    // ========================================================

    // 1. Descargar la plantilla vacía
    public function downloadTemplate(Request $request)
    {
        $categoryId = $request->category_id;

        // Buscamos los productos de esa categoría
        $query = \App\Models\Product::select('id', 'code', 'name', 'price_buy');
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        $products = $query->get();

        // Creamos el archivo CSV en memoria
        $fileName = 'Plantilla_Compras_' . date('Y-m-d') . '.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('ID_INTERNO', 'CODIGO', 'DESCRIPCION', 'COSTO_UNITARIO', 'CANTIDAD_COMPRADA');

        $callback = function() use($products, $columns) {
            $file = fopen('php://output', 'w');

            // IMPORTANTE: Esto arregla las tildes y las ñ al abrir en Excel
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

            // Usamos punto y coma para la separación (estándar en Excel Español)
            fputcsv($file, $columns, ';');

            foreach ($products as $product) {
                $row['ID_INTERNO']  = $product->id;
                $row['CODIGO']    = $product->code;
                $row['DESCRIPCION']  = $product->name;
                $row['COSTO_UNITARIO']  = $product->price_buy;
                $row['CANTIDAD_COMPRADA']  = '0'; // Se deja en 0 para ser llenado

                fputcsv($file, array($row['ID_INTERNO'], $row['CODIGO'], $row['DESCRIPCION'], $row['COSTO_UNITARIO'], $row['CANTIDAD_COMPRADA']), ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 2. Subir y procesar la plantilla
    public function importStock(Request $request)
    {
        // Validamos que se envíen los datos requeridos para la cabecera de la compra
        $request->validate([
            'csv_file' => 'required|file',
            'supplier' => 'required|string',
            'purchase_date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $branchId = $request->branch_id;
        $file = $request->file('csv_file');

        // Abrimos el archivo en modo lectura
        $handle = fopen($file->getPathname(), "r");

        // Saltamos la primera fila (los encabezados)
        fgetcsv($handle, 1000, ";");

        $totalCompra = 0;
        $detalles = [];

        // Leemos fila por fila
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

            // Prevención por si hay filas vacías al final del Excel
            if (!isset($data[0]) || !isset($data[3]) || !isset($data[4])) continue;

            $productId = $data[0];
            $costoUnitario = $data[3];
            $cantidadComprada = $data[4];

            // Solo procesamos si la cantidad es mayor a 0
            if (is_numeric($cantidadComprada) && $cantidadComprada > 0) {
                $subtotal = $costoUnitario * $cantidadComprada;
                $totalCompra += $subtotal;

                $detalles[] = [
                    'product_id' => $productId,
                    'quantity' => $cantidadComprada,
                    'cost_price' => $costoUnitario
                ];
            }
        }
        fclose($handle);

        if (count($detalles) === 0) {
            return back()->with('error', 'El archivo no contiene cantidades válidas mayores a 0 para registrar.');
        }

        try {
            DB::transaction(function () use ($branchId, $totalCompra, $detalles, $request) {

                // 1. Crear Cabecera
                $purchase = \App\Models\Purchase::create([
                    'user_id' => auth()->id(),
                    'branch_id' => $branchId,
                    'supplier' => $request->supplier,
                    'purchase_date' => $request->purchase_date,
                    'total_cost' => $totalCompra
                ]);

                // 2. Procesar detalles de la importación
                foreach ($detalles as $det) {

                    // Guardar detalle
                    $purchase->details()->create([
                        'product_id' => $det['product_id'],
                        'quantity' => $det['quantity'],
                        'cost_price' => $det['cost_price']
                    ]);

                    // 3. ACTUALIZACIÓN DE STOCK
                    $product = \App\Models\Product::find($det['product_id']);

                    if($product) {
                        // A. Actualizar precio de compra global
                        $product->update(['price_buy' => $det['cost_price']]);

                        // B. Sumar al stock GLOBAL
                        $product->increment('stock', $det['quantity']);

                        // C. Actualizar STOCK ESPECÍFICO DE LA SUCURSAL
                        $branchProduct = $product->branches()->where('branch_id', $branchId)->first();

                        if ($branchProduct) {
                            $newStock = $branchProduct->pivot->stock + $det['quantity'];
                            $product->branches()->updateExistingPivot($branchId, [
                                'stock' => $newStock
                            ]);
                        } else {
                            $product->branches()->attach($branchId, [
                                'stock' => $det['quantity']
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('purchases.index')->with('success', 'Importación masiva registrada y stock actualizado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al importar compra: ' . $e->getMessage());
        }
    }
}
