<x-app>
    {{-- Estilos específicos --}}
    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .focus-gold:focus { --tw-ring-color: #C59D5F; border-color: #C59D5F; }
        .font-serif-display { font-family: 'Playfair Display', serif; }

        /* Botón Principal (Nuevo Producto) */
        .btn-gold {
            background: linear-gradient(135deg, #C59D5F 0%, #a37f45 100%);
            color: white;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d6ad6d 0%, #b89050 100%);
            box-shadow: 0 4px 6px -1px rgba(197, 157, 95, 0.4);
        }

        /* Botón Secundario (Inventario) */
        .btn-dark {
            background-color: #171717; /* Neutral 900 */
            color: #C59D5F;
            border: 1px solid #C59D5F;
        }
        .btn-dark:hover {
            background-color: #262626;
            color: white;
        }
    </style>

    {{-- ENCABEZADO Y ACCIONES --}}
    <div class="flex flex-col xl:flex-row justify-between items-end mb-8 gap-6">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Inventario de Productos</h1>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm ml-4">
                Gestionando existencias de: <span class="font-bold text-[#C59D5F]">{{ auth()->user()->branch->name ?? 'Todas las Sucursales' }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
            {{-- Buscador --}}
            <form action="{{ route('products.index') }}" method="GET" class="relative flex-grow xl:flex-grow-0 min-w-[250px]">
                <input type="text" name="search" placeholder="Buscar producto..." value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-sm text-sm focus:outline-none focus:ring-1 focus-gold dark:bg-neutral-900 dark:border-neutral-700 dark:text-white placeholder-gray-400 shadow-sm transition">
                <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>

            {{-- Botones de Acción --}}
            <div class="flex gap-2 w-full sm:w-auto justify-end">
                <button onclick="document.getElementById('inventoryModal').showModal()" class="btn-dark font-bold py-2.5 px-4 rounded-sm shadow-sm transition flex items-center gap-2 text-xs uppercase tracking-wide whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Reporte Físico
                </button>

                <a href="{{ route('categories.index') }}" class="bg-gray-100 hover:bg-gray-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 text-gray-600 dark:text-gray-300 font-bold py-2.5 px-4 rounded-sm border border-gray-200 dark:border-neutral-700 transition flex items-center gap-2 text-xs uppercase tracking-wide whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Categorías
                </a>

                <a href="{{ route('products.create') }}" class="btn-gold font-bold py-2.5 px-6 rounded-sm shadow-md transition transform hover:-translate-y-0.5 flex items-center gap-2 text-xs uppercase tracking-wide whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Nuevo
                </a>
            </div>
        </div>
    </div>

    {{-- TABLA DE PRODUCTOS --}}
    <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F] transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-neutral-900 text-white text-xs uppercase tracking-widest border-b border-gray-200 dark:border-neutral-700">
                        <th class="px-6 py-4 text-left font-bold">Producto</th>
                        <th class="px-6 py-4 text-left font-bold">Categoría</th>
                        <th class="px-6 py-4 text-left font-bold">Precio</th>
                        <th class="px-6 py-4 text-center font-bold">Stock</th>
                        <th class="px-6 py-4 text-center font-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-neutral-700">
                    @forelse($products as $product)

                    @php
                        // STOCK ACTUAL Y LÓGICA DE COLOR
                        $currentStock = $product->stock_actual;
                        $stockClass = match(true) {
                            $currentStock < 5 => 'bg-red-50 text-red-600 border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800',
                            $currentStock < 15 => 'bg-yellow-50 text-yellow-600 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800',
                            default => 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800'
                        };
                    @endphp

                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50 transition bg-white dark:bg-neutral-800 group">

                        {{-- IMAGEN Y NOMBRE --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-14 h-14 relative rounded-md overflow-hidden border border-gray-200 dark:border-neutral-600 group-hover:border-[#C59D5F] transition-colors">
                                    @if($product->image_path)
                                        <img class="w-full h-full object-cover" src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" />
                                    @else
                                        <div class="w-full h-full bg-gray-100 dark:bg-neutral-700 flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-900 dark:text-white font-bold text-sm font-serif-display">{{ $product->name }}</p>
                                    <div class="flex flex-col gap-1 mt-1">
                                        <span class="text-[10px] text-gray-500 dark:text-gray-400 font-mono tracking-wide">
                                            COD: <span class="text-gray-700 dark:text-gray-300 font-bold">{{ $product->code }}</span>
                                        </span>
                                        @if($product->batch)
                                            <span class="text-[9px] text-[#C59D5F] uppercase tracking-wide font-bold">
                                                Lote: {{ $product->batch }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- CATEGORÍA --}}
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                                {{ $product->category->name }}
                            </span>
                        </td>

                        {{-- PRECIO --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900 dark:text-white font-serif-display text-lg">
                                    Bs {{ number_format($product->price_sell, 2) }}
                                </span>
                                @if($product->price_buy)
                                    <span class="text-[10px] text-gray-400 dark:text-gray-600 font-mono" title="Precio de Compra">
                                        PC: {{ number_format($product->price_buy, 2) }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- STOCK --}}
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 font-bold text-xs rounded-full border {{ $stockClass }}">
                                @if($currentStock < 10)
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                @endif
                                {{ $currentStock }} un.
                            </span>
                        </td>

                        {{-- ACCIONES --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-gray-400 hover:text-[#C59D5F] hover:bg-[#C59D5F]/10 rounded-full transition" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>

                                <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>

                                <button onclick="confirmDelete(event, 'delete-form-{{ $product->id }}')" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition" title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <div class="p-4 bg-gray-50 dark:bg-neutral-900 rounded-full mb-3">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <p class="font-medium">No se encontraron productos.</p>
                                <a href="{{ route('products.create') }}" class="text-[#C59D5F] hover:underline mt-2 text-xs uppercase font-bold">Crear el primero</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        @if(method_exists($products, 'links'))
            <div class="px-6 py-4 bg-white dark:bg-neutral-800 border-t border-gray-100 dark:border-neutral-700">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL PARA IMPRIMIR --}}
    <dialog id="inventoryModal" class="p-0 rounded-sm shadow-2xl border-0 w-96 backdrop:bg-neutral-900/80 bg-transparent">
        <div class="bg-white dark:bg-neutral-800 border-t-4 border-[#C59D5F]">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white font-serif-display">Reporte de Inventario</h3>
                    <button onclick="document.getElementById('inventoryModal').close()" class="text-gray-400 hover:text-[#C59D5F] transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('inventory.print') }}" method="GET" target="_blank">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                        Generar PDF de stock físico actual para: <br>
                        <strong class="text-[#C59D5F]">{{ auth()->user()->branch->name ?? 'Sucursal Actual' }}</strong>
                    </p>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Filtrar por Categoría</label>
                        <div class="relative">
                            <select name="category_id" class="w-full p-2.5 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white outline-none focus:ring-1 focus-gold text-sm appearance-none cursor-pointer">
                                <option value="">-- TODAS LAS CATEGORÍAS --</option>
                                @foreach(\App\Models\Category::all() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-neutral-700">
                        <button type="button" onclick="document.getElementById('inventoryModal').close()" class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition">
                            Cancelar
                        </button>
                        <button type="submit" class="btn-gold font-bold py-2 px-6 rounded-sm text-sm uppercase tracking-wide shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Generar PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        function confirmDelete(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto. El producto se eliminará globalmente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C59D5F', // Dorado
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
</x-app>
