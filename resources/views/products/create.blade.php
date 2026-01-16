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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">
                        Registrar Nuevo Producto
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Ingresa los detalles técnicos y comerciales para el catálogo.
                    </p>
                </div>
            </div>

            <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-[#C59D5F] dark:text-gray-500 dark:hover:text-white flex items-center gap-2 text-xs font-bold uppercase tracking-wider transition group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver
            </a>
        </div>

        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F] transition-colors p-8">

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Stock inicial oculto (se maneja por compras) --}}
                <input type="hidden" name="stock" value="0">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                    {{-- COLUMNA IZQUIERDA (DATOS TÉCNICOS) --}}
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Categoría</label>
                            <div class="flex gap-2">
                                <div class="relative w-full">
                                    <select name="category_id" required
                                        class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none appearance-none transition font-medium cursor-pointer">
                                        <option value="" disabled selected>Seleccione...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                                <a href="{{ route('categories.index') }}" class="bg-neutral-100 dark:bg-neutral-700 text-gray-500 dark:text-gray-300 px-4 py-2 rounded-sm font-bold hover:bg-gray-200 dark:hover:bg-neutral-600 transition flex items-center justify-center border border-gray-200 dark:border-neutral-600" title="Nueva Categoría">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </a>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Código de Barras / Manual</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </span>
                                <input type="text" name="code" required placeholder="Ej: LEN-001"
                                    class="w-full pl-10 p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-mono text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Lote (Opcional)</label>
                            <input type="text" name="batch" placeholder="Lote de fabricación"
                                class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition text-sm">
                        </div>

                        <div class="bg-gray-50 dark:bg-neutral-900/50 p-3 rounded-sm border border-gray-100 dark:border-neutral-700 flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-xs text-gray-500 dark:text-gray-400">El stock inicial será <strong>0</strong>. Ingresa mercadería desde el módulo de Compras.</p>
                        </div>
                    </div>

                    {{-- COLUMNA DERECHA (DATOS COMERCIALES) --}}
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre del Producto</label>
                            <input type="text" name="name" required placeholder="Ej: Lente Blue Cut..."
                                class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium text-lg">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Precio Compra</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-400 text-xs">Bs</span>
                                    <input type="number" step="0.01" name="price_buy" placeholder="0.00"
                                        class="w-full pl-8 p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-mono text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#C59D5F] uppercase tracking-wider mb-2">Precio Venta</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-[#C59D5F] text-xs">Bs</span>
                                    <input type="number" step="0.01" name="price_sell" required placeholder="0.00"
                                        class="w-full pl-8 p-3 border border-[#C59D5F]/30 rounded-sm bg-white dark:bg-neutral-800 dark:border-neutral-600 text-gray-900 dark:text-white focus:ring-1 focus-gold outline-none transition font-bold text-lg font-serif-display">
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN IMAGEN --}}
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-sm p-2 bg-gray-50 dark:bg-neutral-900/50 hover:bg-gray-100 dark:hover:bg-neutral-800/50 transition relative group h-40 flex flex-col justify-center items-center">

                            <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(this)" accept="image/*">

                            <div id="upload-placeholder" class="text-center pointer-events-none">
                                <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-sm text-[#C59D5F] font-bold">Subir Foto del Producto</p>
                                <p class="text-xs text-gray-400 mt-1">Click o arrastra aquí</p>
                            </div>

                            <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-contain p-2 rounded-sm z-0" />
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-100 dark:border-neutral-700 flex justify-end gap-4">
                    <a href="{{ route('products.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-gold font-bold py-2.5 px-8 rounded-sm shadow-md transform transition hover:-translate-y-0.5 flex items-center gap-2 text-sm uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script para manejar la previsualización --}}
    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app>
