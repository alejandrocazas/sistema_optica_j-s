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

    {{-- ENCABEZADO Y BOTONES --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Listado de Pacientes</h1>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm ml-4">Gestiona la información y el historial clínico.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-end">
            {{-- BUSCADOR --}}
            <form action="{{ route('patients.index') }}" method="GET" class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, CI..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-sm text-sm focus:outline-none focus:ring-1 focus-gold dark:bg-neutral-900 dark:border-neutral-700 dark:text-white placeholder-gray-400 shadow-sm transition">
                <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>

            {{-- BOTÓN NUEVO --}}
            <a href="{{ route('patients.create') }}" class="btn-gold font-bold py-2.5 px-6 rounded-sm shadow-md flex items-center gap-2 transition transform hover:-translate-y-0.5 whitespace-nowrap text-sm uppercase tracking-wider w-full sm:w-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                Nuevo Paciente
            </a>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F] transition-colors">

        @if($patients->isEmpty())
            <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                <div class="flex flex-col items-center justify-center">
                    <div class="p-4 bg-gray-50 dark:bg-neutral-900 rounded-full mb-3">
                        <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <p class="text-sm font-medium">No se encontraron pacientes registrados.</p>
                    @if(request('search'))
                        <a href="{{ route('patients.index') }}" class="text-[#C59D5F] hover:text-[#a37f45] hover:underline mt-2 text-xs uppercase font-bold tracking-wide">Limpiar búsqueda</a>
                    @endif
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-neutral-700 bg-neutral-900 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Nombre
                            </th>
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-neutral-700 bg-neutral-900 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Teléfono
                            </th>
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-neutral-700 bg-neutral-900 text-left text-xs font-bold text-white uppercase tracking-wider">
                                CI / DNI
                            </th>
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-neutral-700 bg-neutral-900 text-center text-xs font-bold text-white uppercase tracking-wider">
                                Edad
                            </th>
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-neutral-700 bg-neutral-900 text-center text-xs font-bold text-white uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-700">
                        @foreach($patients as $patient)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50 transition bg-white dark:bg-neutral-800 group">

                            <td class="px-5 py-4 text-sm">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-neutral-100 dark:bg-neutral-700 text-gray-500 dark:text-gray-300 rounded-full flex items-center justify-center font-bold uppercase text-xs border-2 border-transparent group-hover:border-[#C59D5F] transition-colors">
                                        {{ substr($patient->name, 0, 2) }}
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-900 dark:text-white font-bold text-sm whitespace-no-wrap font-serif-display">
                                            {{ $patient->name }}
                                        </p>
                                        <p class="text-gray-400 dark:text-gray-500 text-[10px] uppercase tracking-wide">{{ $patient->occupation ?? 'Sin ocupación' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-sm">
                                <span class="text-gray-600 dark:text-gray-300 font-mono text-xs bg-gray-50 dark:bg-neutral-900 px-2 py-1 rounded border border-gray-200 dark:border-neutral-700">
                                    {{ $patient->phone ?? '-' }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-sm">
                                <p class="text-gray-900 dark:text-white font-mono text-xs font-bold">{{ $patient->ci }}</p>
                            </td>

                            <td class="px-5 py-4 text-sm text-center">
                                <span class="inline-block px-3 py-1 font-bold text-xs leading-tight text-[#C59D5F] bg-[#C59D5F]/10 rounded-full border border-[#C59D5F]/20">
                                    {{ $patient->age }} años
                                </span>
                            </td>

                            <td class="px-5 py-4 text-sm text-center">
                                <div class="flex justify-center items-center gap-3">
                                    {{-- Editar --}}
                                    <a href="{{ route('patients.edit', $patient->id) }}" class="text-gray-400 hover:text-[#C59D5F] transition transform hover:scale-110" title="Editar Datos">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>

                                    {{-- Historial --}}
                                    <a href="{{ route('prescriptions.history', $patient->id) }}" class="text-gray-400 hover:text-blue-500 transition transform hover:scale-110" title="Ver Historial Médico">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                        </svg>
                                    </a>

                                    {{-- Eliminar --}}
                                    <form id="delete-form-{{ $patient->id }}" action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display: none;">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button onclick="confirmDelete(event, 'delete-form-{{ $patient->id }}')" class="text-gray-400 hover:text-red-500 transition transform hover:scale-110" title="Eliminar Paciente">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            @if(method_exists($patients, 'links'))
                <div class="px-5 py-4 bg-white dark:bg-neutral-800 border-t border-gray-100 dark:border-neutral-700">
                    {{ $patients->links() }}
                </div>
            @endif
        @endif
    </div>

    <script>
        function confirmDelete(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se eliminará el paciente y todo su historial médico.",
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
