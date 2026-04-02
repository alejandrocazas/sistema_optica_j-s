<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // 1. Mostrar filtro (Seleccionar Categoría)
    public function index()
    {
        $categories = \App\Models\Category::all();
        return view('inventory.index', compact('categories'));
    }

    // 2. Generar el PDF
    public function print(Request $request)
    {
        // 1. Damos un respiro al servidor (Parche de seguridad)
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        // 2. Obtenemos los filtros
        $branchId = auth()->user()->branch_id;
        $categoryId = $request->category_id;

        // 3. CONSULTA OPTIMIZADA: No traemos campos innecesarios
        // Ajusta el nombre de la tabla pivote de sucursales según tu base de datos
        $query = \App\Models\Product::select('id', 'code', 'name', 'category_id')
            ->whereHas('branches', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->with(['branches' => function($q) use ($branchId) {
                // Solo traemos el stock de esta sucursal específica
                $q->where('branch_id', $branchId)->select('branch_id', 'product_id', 'stock');
            }]);

        // Si filtró por categoría
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Ejecutamos la consulta
        $products = $query->get();
        $branch = \App\Models\Branch::find($branchId);

        // 4. Generamos el PDF
        $pdf = \PDF::loadView('reports.inventory-pdf', compact('products', 'branch'));

        // return $pdf->download('inventario.pdf'); // Si quieres que se descargue directo
        return $pdf->stream('Inventario_' . $branch->name . '.pdf'); // Para verlo en el navegador
    }
}
