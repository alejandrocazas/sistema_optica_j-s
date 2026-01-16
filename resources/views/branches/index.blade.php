<x-app>
    {{-- Estilos específicos --}}
    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .focus-gold:focus { --tw-ring-color: #C59D5F; border-color: #C59D5F; }

        .btn-gold {
            background: linear-gradient(135deg, #C59D5F 0%, #a37f45 100%);
            color: white;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d6ad6d 0%, #b89050 100%);
            box-shadow: 0 4px 6px -1px rgba(197, 157, 95, 0.4);
        }
    </style>

    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Sucursales</h1>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm ml-4">Gestiona tus diferentes ópticas y locales comerciales.</p>
        </div>

        <button onclick="document.getElementById('createBranchModal').showModal()" class="btn-gold font-bold py-2.5 px-6 rounded-sm shadow-md flex items-center gap-2 transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wider">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Nueva Sucursal
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($branches as $branch)
        <div class="bg-white dark:bg-neutral-800 rounded-sm shadow-xl border-t-4 border-[#C59D5F] overflow-hidden hover:shadow-2xl transition duration-300 group">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    {{-- Icono de Tienda --}}
                    <div class="p-3 bg-neutral-100 dark:bg-neutral-700 text-gray-500 dark:text-gray-300 rounded-full group-hover:bg-[#C59D5F]/10 group-hover:text-[#C59D5F] transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex gap-2">
                        <button onclick="openEditModal({{ $branch }})" class="p-2 text-gray-400 hover:text-[#C59D5F] hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-full transition" title="Editar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        @if($branch->id !== 1)
                            <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta sucursal?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <h3 class="mt-5 text-xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">{{ $branch->name }}</h3>

                <div class="mt-3 space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $branch->address ?? 'Sin dirección registrada' }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        {{ $branch->phone ?? 'Sin teléfono' }}
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-neutral-700 flex justify-between items-center">
                    <span class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Personal Asignado</span>
                    <span class="bg-neutral-900 text-[#C59D5F] border border-[#C59D5F] text-xs font-bold px-3 py-1 rounded-full">
                        {{ $branch->users_count }} Usuarios
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- MODAL CREAR --}}
    <dialog id="createBranchModal" class="p-0 rounded-sm shadow-2xl w-full max-w-md backdrop:bg-neutral-900/80 bg-transparent">
        <div class="bg-white dark:bg-neutral-800 border-t-4 border-[#C59D5F] overflow-hidden">
            <div class="p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 font-serif-display">Nueva Sucursal</h3>

                <form action="{{ route('branches.store') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre Comercial</label>
                        <input type="text" name="name" required placeholder="Ej: Óptica Central"
                            class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Dirección</label>
                        <input type="text" name="address" placeholder="Av. Principal #123"
                            class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                    </div>
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Teléfono</label>
                        <input type="text" name="phone" placeholder="(591) ..."
                            class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-neutral-700">
                        <button type="button" onclick="document.getElementById('createBranchModal').close()" class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">Cancelar</button>
                        <button type="submit" class="btn-gold font-bold py-2 px-6 rounded-sm text-sm uppercase tracking-wide shadow-md transform transition hover:-translate-y-0.5">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    {{-- MODAL EDITAR --}}
    <dialog id="editBranchModal" class="p-0 rounded-sm shadow-2xl w-full max-w-md backdrop:bg-neutral-900/80 bg-transparent">
        <div class="bg-white dark:bg-neutral-800 border-t-4 border-[#C59D5F] overflow-hidden">
            <div class="p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 font-serif-display">Editar Sucursal</h3>

                <form id="editForm" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre</label>
                        <input type="text" id="editName" name="name" required
                            class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Dirección</label>
                        <input type="text" id="editAddress" name="address"
                            class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                    </div>
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Teléfono</label>
                        <input type="text" id="editPhone" name="phone"
                            class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-neutral-700">
                        <button type="button" onclick="document.getElementById('editBranchModal').close()" class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">Cancelar</button>
                        <button type="submit" class="btn-gold font-bold py-2 px-6 rounded-sm text-sm uppercase tracking-wide shadow-md transform transition hover:-translate-y-0.5">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        function openEditModal(branch) {
            document.getElementById('editName').value = branch.name;
            document.getElementById('editAddress').value = branch.address;
            document.getElementById('editPhone').value = branch.phone;
            document.getElementById('editForm').action = `/branches/${branch.id}`;
            document.getElementById('editBranchModal').showModal();
        }
    </script>
</x-app>
