<x-app>
    {{-- Estilos específicos --}}
    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
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

    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Gestión de Personal</h1>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm ml-4">Administra los usuarios, sus roles y ubicación.</p>
        </div>

        <a href="{{ route('users.create') }}" class="btn-gold font-bold py-2.5 px-6 rounded-sm shadow-md flex items-center gap-2 transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wider">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Nuevo Usuario
        </a>
    </div>

    <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F] transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-neutral-700 bg-neutral-900 text-left text-xs font-bold text-white uppercase tracking-wider">
                            Nombre
                        </th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-neutral-700 bg-neutral-900 text-left text-xs font-bold text-white uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-neutral-700 bg-neutral-900 text-center text-xs font-bold text-white uppercase tracking-wider">
                            Sucursal
                        </th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-neutral-700 bg-neutral-900 text-center text-xs font-bold text-white uppercase tracking-wider">
                            Rol
                        </th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-neutral-700 bg-neutral-900 text-center text-xs font-bold text-white uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-neutral-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50 transition bg-white dark:bg-neutral-800 group">

                        <td class="px-5 py-4 text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-neutral-100 dark:bg-neutral-700 text-gray-600 dark:text-gray-300 rounded-full flex items-center justify-center font-bold uppercase border-2 border-transparent group-hover:border-[#C59D5F] transition-colors">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-900 dark:text-white font-bold whitespace-no-wrap font-serif-display">
                                        {{ $user->name }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm">
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-xs md:text-sm">
                                {{ $user->email }}
                            </p>
                        </td>

                        {{-- DATO DE SUCURSAL --}}
                        <td class="px-5 py-4 text-sm text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-neutral-700 dark:text-gray-300 border border-gray-200 dark:border-neutral-600">
                                {{ $user->branch->name ?? 'Global / Sin Asignar' }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-sm text-center">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-neutral-900 text-[#C59D5F] border border-[#C59D5F]">
                                    Administrador
                                </span>
                            @elseif($user->role === 'optometrista')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#C59D5F]/10 text-[#C59D5F] border border-[#C59D5F]/30">
                                    Optometrista
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 dark:bg-neutral-700 dark:text-gray-300 border border-gray-200 dark:border-neutral-600">
                                    Vendedor
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-sm text-center">
                            <div class="flex justify-center items-center gap-2">

                                {{-- Editar --}}
                                <a href="{{ route('users.edit', $user) }}" class="p-2 text-gray-400 hover:text-[#C59D5F] hover:bg-[#C59D5F]/10 rounded-full transition" title="Editar Usuario">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>

                                {{-- Eliminar (Solo si no es uno mismo) --}}
                                @if(auth()->id() !== $user->id)
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button onclick="confirmDelete(event, 'delete-form-{{ $user->id }}')" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition" title="Eliminar Usuario">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="p-2 text-gray-300 dark:text-neutral-600 cursor-not-allowed" title="No puedes eliminarte a ti mismo">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        @if(method_exists($users, 'links'))
            <div class="px-5 py-4 bg-white dark:bg-neutral-800 border-t border-gray-100 dark:border-neutral-700">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <script>
        function confirmDelete(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "El usuario perderá acceso al sistema inmediatamente.",
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
