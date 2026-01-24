<x-app>
    {{-- Estilos específicos y Scripts del Modal --}}
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
        /* Estilos del Modal */
        #diagnosticModal {
            transition: opacity 0.3s ease;
        }
    </style>

    {{-- ENCABEZADO --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Reporte de Atenciones</h1>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm ml-4">Listado completo de todas las consultas realizadas.</p>
        </div>

        <div class="flex gap-3">
            {{-- BOTÓN AGREGAR DIAGNÓSTICO (NUEVO) --}}
            <button onclick="openModal()" class="bg-white dark:bg-neutral-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-neutral-600 font-bold py-2.5 px-4 rounded-sm shadow-sm flex items-center gap-2 transition hover:bg-gray-50 dark:hover:bg-neutral-600 text-sm uppercase tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C59D5F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Diagnóstico
            </button>

            <a href="{{ route('prescriptions.selectPatient') }}" class="btn-gold font-bold py-2.5 px-6 rounded-sm shadow-md flex items-center gap-2 transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Consulta
            </a>
        </div>
    </div>

    @if($prescriptions->isEmpty())
        <div class="bg-white dark:bg-neutral-800 p-12 rounded-sm shadow-md text-center border-t-4 border-gray-200 dark:border-neutral-700">
            <div class="inline-block p-4 rounded-full bg-gray-50 dark:bg-neutral-900 mb-3">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <p class="text-xl mb-1 text-gray-600 dark:text-gray-300 font-serif-display">Sin registros clínicos</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm">Aún no se han realizado consultas en el sistema.</p>
        </div>
    @else
        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F] transition-colors">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-neutral-900 text-white text-xs uppercase tracking-widest border-b border-gray-200 dark:border-neutral-700">
                            <th class="px-6 py-4 text-left font-bold">Fecha / Hora</th>
                            <th class="px-6 py-4 text-left font-bold">Paciente</th>
                            <th class="px-6 py-4 text-left font-bold">Diagnóstico</th>
                            <th class="px-6 py-4 text-center font-bold">Atendido Por</th>
                            <th class="px-6 py-4 text-center font-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-700 text-gray-700 dark:text-gray-300">
                        @foreach($prescriptions as $prescription)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50 transition bg-white dark:bg-neutral-800 group">
                            {{-- FECHA --}}
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-neutral-100 dark:bg-neutral-700 rounded-sm text-gray-400 dark:text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold font-serif-display text-gray-900 dark:text-white text-base">
                                            {{ $prescription->created_at->format('d/m/Y') }}
                                        </p>
                                        <p class="text-xs text-gray-400 font-mono mt-0.5">
                                            {{ $prescription->created_at->format('H:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- PACIENTE --}}
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-9 h-9 bg-gray-100 dark:bg-neutral-700 rounded-full flex items-center justify-center font-bold uppercase text-xs text-gray-500 border-2 border-transparent group-hover:border-[#C59D5F] transition-colors">
                                        {{ substr($prescription->patient->name, 0, 2) }}
                                    </div>
                                    <div class="ml-3">
                                        <a href="{{ route('prescriptions.history', $prescription->patient_id) }}" class="text-gray-900 dark:text-white font-bold hover:text-[#C59D5F] dark:hover:text-[#C59D5F] hover:underline transition">
                                            {{ $prescription->patient->name }}
                                        </a>
                                        <p class="text-[10px] text-gray-400 uppercase tracking-wide mt-0.5">
                                            CI: {{ $prescription->patient->ci }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- DIAGNÓSTICO --}}
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-block bg-[#C59D5F]/10 text-[#C59D5F] text-[10px] px-2 py-1 rounded-sm font-bold uppercase tracking-wide border border-[#C59D5F]/20 mb-1">
                                    {{ $prescription->diagnostico ?? 'General' }}
                                </span>
                                @if($prescription->observaciones)
                                    <p class="text-gray-500 dark:text-gray-400 text-xs truncate w-48 italic border-l-2 border-gray-200 dark:border-neutral-600 pl-2">
                                        {{ $prescription->observaciones }}
                                    </p>
                                @endif
                            </td>

                            {{-- ATENDIDO POR --}}
                            <td class="px-6 py-4 text-center">
                                <span class="text-gray-500 dark:text-gray-400 font-medium text-xs bg-gray-100 dark:bg-neutral-700 px-3 py-1 rounded-full border border-gray-200 dark:border-neutral-600">
                                    {{ $prescription->user->name }}
                                </span>
                            </td>

                            {{-- ACCIONES --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('prescriptions.print', $prescription->id) }}" target="_blank" class="p-2 text-gray-400 hover:text-gray-800 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-full transition" title="Imprimir Receta">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('prescriptions.history', $prescription->patient_id) }}" class="p-2 text-[#C59D5F] hover:text-[#a37f45] hover:bg-[#C59D5F]/10 rounded-full transition" title="Ver Historial Clínico">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            @if(method_exists($prescriptions, 'links'))
                <div class="px-6 py-4 bg-white dark:bg-neutral-800 border-t border-gray-100 dark:border-neutral-700">
                    {{ $prescriptions->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- MODAL AGREGAR DIAGNÓSTICO --}}
    <div id="diagnosticModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('diagnostics.store') }}" method="POST">
                    @csrf
                    <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[#C59D5F]/10 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-[#C59D5F]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white font-serif-display" id="modal-title">
                                    Nuevo Diagnóstico
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                        Agrega un nuevo diagnóstico clínico para que esté disponible en la lista de selección de recetas.
                                    </p>
                                    <input type="text" name="name" required placeholder="Ej: Miopía Magna"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-neutral-600 rounded-sm bg-white dark:bg-neutral-900 text-gray-900 dark:text-white focus:outline-none focus:border-[#C59D5F] focus:ring-1 focus:ring-[#C59D5F]">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-neutral-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-sm border border-transparent shadow-sm px-4 py-2 bg-[#C59D5F] text-base font-medium text-white hover:bg-[#a37f45] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C59D5F] sm:ml-3 sm:w-auto sm:text-sm">
                            Guardar
                        </button>
                        <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-sm border border-gray-300 dark:border-neutral-500 shadow-sm px-4 py-2 bg-white dark:bg-neutral-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPTS DEL MODAL --}}
    <script>
        function openModal() {
            document.getElementById('diagnosticModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('diagnosticModal').classList.add('hidden');
        }
    </script>

</x-app>
