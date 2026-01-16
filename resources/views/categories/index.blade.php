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

    <div class="max-w-5xl mx-auto">

        {{-- ENCABEZADO --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Categorías</h1>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm ml-4">Clasificación y organización de productos.</p>
            </div>

            <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-[#C59D5F] dark:text-gray-400 dark:hover:text-white flex items-center gap-2 text-xs font-bold uppercase tracking-wider transition group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a Productos
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- FORMULARIO DE CREACIÓN --}}
            <div class="md:col-span-1">
                <div class="bg-white dark:bg-neutral-800 p-6 rounded-sm shadow-xl border-t-4 border-[#C59D5F] sticky top-24">
                    <h2 class="font-bold text-lg mb-6 text-gray-800 dark:text-white flex items-center gap-2 font-serif-display">
                        <span class="p-1.5 bg-[#C59D5F]/10 rounded-full text-[#C59D5F]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Nueva Categoría
                    </h2>

                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre</label>
                            <input type="text" name="name" placeholder="Ej: Monturas, Líquidos..."
                                class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                        </div>
                        <button class="w-full btn-gold font-bold py-3 px-4 rounded-sm shadow-md transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wider">
                            Guardar
                        </button>
                    </form>
                </div>
            </div>

            {{-- LISTADO DE CATEGORÍAS --}}
            <div class="md:col-span-2">
                <div class="bg-white dark:bg-neutral-800 rounded-sm shadow-xl overflow-hidden border border-gray-100 dark:border-neutral-700">
                    <div class="bg-neutral-900 px-6 py-4 border-b border-gray-800">
                        <h3 class="font-bold text-white uppercase text-xs tracking-widest">Listado Existente</h3>
                    </div>

                    @if($categories->isEmpty())
                        <div class="p-10 text-center text-gray-400 dark:text-gray-500">
                            <div class="inline-block p-4 rounded-full bg-gray-50 dark:bg-neutral-900 mb-3">
                                <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <p class="text-sm">No hay categorías registradas aún.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-100 dark:divide-neutral-700">
                            @foreach($categories as $category)
                                <li class="px-6 py-5 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-neutral-700/30 transition group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-1.5 h-1.5 bg-[#C59D5F] rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        <span class="font-bold text-gray-800 dark:text-white font-serif-display text-lg">{{ $category->name }}</span>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        {{-- Badge Contador --}}
                                        <span class="bg-neutral-900 text-[#C59D5F] border border-[#C59D5F]/30 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                                            {{ $category->products_count }} productos
                                        </span>

                                        @if($category->products_count == 0)
                                            <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: none;">
                                                @csrf @method('DELETE')
                                            </form>

                                            <button onclick="confirmDelete(event, 'delete-form-{{ $category->id }}')" class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition" title="Eliminar Categoría">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @else
                                            <span class="p-2 text-gray-200 dark:text-neutral-700 cursor-not-allowed" title="No se puede eliminar porque tiene productos asociados">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function confirmDelete(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '¿Eliminar Categoría?',
                text: "Esta acción es irreversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C59D5F', // Dorado
                cancelButtonColor: '#6B7280',  // Gris
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
