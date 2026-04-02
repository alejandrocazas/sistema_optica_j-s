<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Listar productos con sus categorías
    public function index(Request $request)
{
    // 1. Iniciamos la consulta cargando la relación 'category' para optimizar
    $query = \App\Models\Product::with('category')->latest();

    // 2. Lógica del Buscador (Si escriben algo en el input)
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")       // Buscar por nombre
              ->orWhere('code', 'like', "%{$search}%");    // Buscar por código
        });
    }

    // 3. Paginamos (10 productos por página)
    // Importante: Usar paginate() es lo que habilita el método ->links() en la vista
    $products = $query->paginate(10);

    return view('products.index', compact('products'));
}

    // Formulario de creación (Necesitamos enviar las categorías para el Select)
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // Guardar producto
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            // --- CORRECCIÓN AQUÍ: unique:tabla,columna ---
            'code' => 'required|string|max:255|unique:products,code',
            // ----------------------------------------------
            'name' => 'required',
            'stock' => 'required|numeric',
            'price_buy' => 'required|numeric', // <-- No olvides validar el precio de compra también
            'price_sell' => 'required|numeric',
            'image' => 'nullable|image|max:2048' // Máximo 2MB
        ], [
            // --- NUEVO: Mensajes personalizados ---
            'code.unique' => 'Este código ya está en uso por otro producto. Ingresa uno diferente.',
            'code.required' => 'El código del producto es obligatorio.'
            // -------------------------------------
        ]);

        $data = $request->all();

        // Subir Imagen (Tu código estaba bien)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Producto registrado.');
    }

    // Borrar producto e imagen
    public function destroy(Product $product)
    {
        $product->delete(); // Esto ahora solo lo ocultará gracias al SoftDeletes
        return redirect()->route('products.index')->with('success', 'Producto desactivado correctamente.');
    }
    // Muestra el formulario de edición
    public function edit(Product $product)
    {
        $categories = Category::all(); // Necesitamos la lista para el select
        return view('products.edit', compact('product', 'categories'));
    }

    // Guarda los cambios
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required',
            'code' => 'required|string|max:255|unique:products,code,'.$product->id,
            'name' => 'required',
            'price_buy' => 'required|numeric',
            'price_sell' => 'required|numeric',
            'stock' => 'sometimes|numeric', // <-- Permite recibir el stock si es admin
            'image' => 'nullable|image|max:2048'
        ], [
            'code.unique' => 'Este código ya está en uso por otro producto. Ingresa uno diferente.',
        ]);

        $data = $request->except('image');

        // Lógica de la Imagen
        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        // --- LÓGICA DE CORRECCIÓN DE STOCK (SOLO PARA ADMIN/SUPERADMIN) ---
        $oldStock = $product->stock;
        $newStock = $request->input('stock', $oldStock); // Si no envían stock, mantiene el viejo
        $diff = $newStock - $oldStock; // Calculamos la diferencia matemática

        // Actualizamos el producto (incluyendo el stock global si cambió)
        $product->update($data);

        // Si hubo un cambio en el stock y el usuario pertenece a una sucursal
        if ($diff != 0 && auth()->user()->branch_id) {
            $branchId = auth()->user()->branch_id;

            // Buscamos el stock específico de esta sucursal
            $branchProduct = $product->branches()->where('branch_id', $branchId)->first();

            if ($branchProduct) {
                // Le sumamos/restamos la diferencia al stock que ya tenía la sucursal
                $product->branches()->updateExistingPivot($branchId, [
                    'stock' => $branchProduct->pivot->stock + $diff
                ]);
            } else {
                // Si la sucursal nunca había tenido este producto, se lo asignamos
                $product->branches()->attach($branchId, ['stock' => $newStock]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }
    // Muestra el formulario de edición

    // Método rápido para guardar categoría desde el mismo formulario (AJAX o simple)
    // Por ahora lo haremos simple, usaremos un controlador aparte para categorias si deseas,
    // o un método storeCategory aquí mismo si queremos simplificar.
}
