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
        // 1. Damos un respiro al servidor
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        // 2. Obtenemos los filtros
        $branchId = auth()->user()->branch_id;
        $categoryId = $request->category_id;

        // --- NUEVO: Buscamos el nombre de la categoría ---
        $categoriaNombre = 'TODAS LAS CATEGORÍAS'; // Por defecto, si imprime todo
        if ($categoryId) {
            $categoria = \App\Models\Category::find($categoryId);
            if ($categoria) {
                $categoriaNombre = $categoria->name;
            }
        }
        // -------------------------------------------------

        // 3. CONSULTA OPTIMIZADA
        $query = \App\Models\Product::select('id', 'code', 'name', 'category_id', 'batch')
            ->whereHas('branches', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->with(['branches' => function($q) use ($branchId) {
                $q->where('branch_id', $branchId)->select('branch_id', 'product_id', 'stock');
            }]);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->get();
        $branch = \App\Models\Branch::find($branchId);

        // 4. Generamos el PDF (Agregamos 'categoriaNombre' al compact y usamos el guion bajo)
        $pdf = \PDF::loadView('reports.inventory_pdf', compact('products', 'branch', 'categoriaNombre'));

        return $pdf->stream('Inventario_' . ($branch->name ?? 'Global') . '.pdf');
    }
}
