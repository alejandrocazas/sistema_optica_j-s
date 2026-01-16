<x-app>
    {{-- Estilos específicos --}}
    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .focus-gold:focus { --tw-ring-color: #C59D5F; border-color: #C59D5F; }
        .font-serif-display { font-family: 'Playfair Display', serif; }

        .btn-gold {
            background: linear-gradient(135deg, #C59D5F 0%, #a37f45 100%);
            color: white;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d6ad6d 0%, #b89050 100%);
            box-shadow: 0 4px 6px -1px rgba(197, 157, 95, 0.4);
        }
    </style>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">

        {{-- ENCABEZADO --}}
        <div class="flex justify-between items-start mb-8 border-b border-gray-100 dark:border-neutral-700 pb-6">
            <div class="flex gap-4">
                <div class="p-3 bg-[#C59D5F]/10 rounded-full text-[#C59D5F]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">
                        Editar Producto
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Modificando ficha de: <span class="font-bold text-[#C59D5F] font-mono">{{ $product->code }}</span>
                    </p>
                </div>
            </div>

            <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-[#C59D5F] dark:text-gray-500 dark:hover:text-white flex items-center gap-2 text-xs font-bold uppercase tracking-wider transition group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Cancelar
            </a>
        </div>

        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F] transition-colors p-8">

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                    {{-- COLUMNA IZQUIERDA (DATOS TÉCNICOS) --}}
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Categoría</label>
                            <div class="relative">
                                <select name="category_id" class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none appearance-none transition font-medium cursor-pointer">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Código de Barras / Manual</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </span>
                                <input type="text" name="code" value="{{ $product->code }}"
                                    class="w-full pl-10 p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-mono text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Lote (Opcional)</label>
                            <input type="text" name="batch" value="{{ $product->batch }}"
                                class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition text-sm">
                        </div>

                        {{-- STOCK BLOQUEADO (SEGURIDAD) --}}
                        <div class="bg-red-50 dark:bg-red-900/10 p-4 rounded-sm border border-red-100 dark:border-red-800/30">
                            <label class="text-xs font-bold text-red-500 dark:text-red-400 mb-2 flex items-center gap-2 uppercase tracking-wide">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Stock Actual (Bloqueado)
                            </label>
                            <input type="number" name="stock" value="{{ $product->stock }}"
                                   class="bg-white dark:bg-neutral-800 text-gray-500 dark:text-gray-400 font-bold cursor-not-allowed border border-gray-200 dark:border-neutral-600 p-2 rounded-sm w-full text-center text-xl font-serif-display"
                                   readonly>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-2 leading-tight">
                                Para modificar el stock, utiliza el módulo de <strong>Compras</strong> o realiza un <strong>Ajuste de Inventario</strong>.
                            </p>
                        </div>
                    </div>

                    {{-- COLUMNA DERECHA (DATOS COMERCIALES) --}}
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre del Producto</label>
                            <input type="text" name="name" value="{{ $product->name }}"
                                class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium text-lg">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Precio Compra</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-400 text-xs">Bs</span>
                                    <input type="number" step="0.01" name="price_buy" value="{{ $product->price_buy }}"
                                        class="w-full pl-8 p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-mono text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#C59D5F] uppercase tracking-wider mb-2">Precio Venta</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-[#C59D5F] text-xs">Bs</span>
                                    <input type="number" step="0.01" name="price_sell" value="{{ $product->price_sell }}"
                                        class="w-full pl-8 p-3 border border-[#C59D5F]/30 rounded-sm bg-white dark:bg-neutral-800 dark:border-neutral-600 text-gray-900 dark:text-white focus:ring-1 focus-gold outline-none transition font-bold text-lg font-serif-display">
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN IMAGEN --}}
                        <div class="border border-dashed border-gray-300 dark:border-neutral-600 rounded-sm p-6 bg-gray-50 dark:bg-neutral-900/50">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Imagen del Producto</label>

                            <div class="flex items-start gap-6">
                                <div class="relative group">
                                    @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" class="w-24 h-24 object-cover rounded-sm border dark:border-neutral-600 shadow-sm">
                                        <div class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center text-white text-[10px] uppercase font-bold rounded-sm">Actual</div>
                                    @else
                                        <div class="w-24 h-24 bg-gray-200 dark:bg-neutral-700 rounded-sm flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 border dark:border-neutral-600">
                                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-[9px]">Sin Foto</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <label class="block">
                                        <span class="sr-only">Elegir archivo</span>
                                        <input type="file" name="image" class="block w-full text-xs text-gray-500 dark:text-gray-400
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-sm file:border-0
                                          file:text-xs file:font-bold file:uppercase file:tracking-wide
                                          file:bg-[#C59D5F]/10 file:text-[#C59D5F]
                                          hover:file:bg-[#C59D5F]/20
                                          cursor-pointer
                                        "/>
                                    </label>
                                    <p class="text-[10px] text-gray-400 mt-2">Formatos: JPG, PNG. Máx 2MB.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-100 dark:border-neutral-700 flex justify-end gap-4">
                    <a href="{{ route('products.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-gold font-bold py-2.5 px-8 rounded-sm shadow-md transform transition hover:-translate-y-0.5 flex items-center gap-2 text-sm uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app>
