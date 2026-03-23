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

                        {{-- 1. COLUMNA DE ESTADO --}}
<td class="px-6 py-4 whitespace-nowrap text-center">
    @if($user->is_active)
        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
            Activo
        </span>
    @else
        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
            Inactivo
        </span>
    @endif
</td>

{{-- 2. COLUMNA DE ACCIONES (Reemplaza tu botón de eliminar por este) --}}
<td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">

    {{-- Botón Editar (Mantenlo como lo tenías) --}}
    <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>

    {{-- NUEVO BOTÓN: Activar / Desactivar --}}
    @if(auth()->id() !== $user->id) {{-- Para que no salga el botón en tu propio usuario --}}
        <form action="{{ route('users.toggle', $user) }}" method="POST" class="inline-block">
            @csrf
            @method('PATCH')
            <button type="submit"
                    class="{{ $user->is_active ? 'text-red-600 hover:text-red-900' : 'text-emerald-600 hover:text-emerald-900' }} font-bold"
                    onclick="return confirm('¿Estás seguro de cambiar el estado de este usuario?')">
                {{ $user->is_active ? 'Desactivar' : 'Activar' }}
            </button>
        </form>
    @endif
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
