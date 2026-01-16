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

    <div class="max-w-3xl mx-auto mt-10">
        <div class="bg-white dark:bg-neutral-800 p-10 rounded-sm shadow-xl transition-colors border-t-4 border-[#C59D5F]">

            {{-- Encabezado --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center p-3 bg-[#C59D5F]/10 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#C59D5F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2 font-serif-display tracking-wide">Nueva Consulta Oftalmológica</h1>
                <p class="text-gray-500 dark:text-gray-400">Busca al paciente por nombre o carnet para iniciar.</p>
            </div>

            {{-- Formulario de Búsqueda --}}
            <form action="{{ route('prescriptions.selectPatient') }}" method="GET" class="relative mb-10">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-gray-400 group-focus-within:text-[#C59D5F] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    <input type="text" name="search" value="{{ $search }}"
                           class="w-full pl-14 pr-32 py-5 border border-gray-200 rounded-full shadow-sm outline-none transition text-lg
                                  bg-gray-50 text-gray-800 focus:bg-white focus:ring-2 focus:ring-[#C59D5F] focus:border-transparent
                                  dark:bg-neutral-900 dark:text-white dark:border-neutral-700 dark:focus:ring-[#C59D5F]"
                           placeholder="Ej: Juan Perez o 1234567..." autofocus autocomplete="off">

                    {{-- Botón Buscar --}}
                    <button type="submit" class="absolute right-2 top-2 bottom-2 bg-neutral-900 hover:bg-black dark:bg-[#C59D5F] dark:hover:bg-[#a37f45] text-white font-bold py-2 px-8 rounded-full transition shadow-md uppercase text-xs tracking-widest">
                        Buscar
                    </button>
                </div>
            </form>

            {{-- Resultados --}}
            @if($search)
                @if(count($patients) > 0)
                    <div class="text-left mb-4 flex items-center gap-2">
                        <span class="h-px flex-1 bg-gray-200 dark:bg-neutral-700"></span>
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Resultados Encontrados</span>
                        <span class="h-px flex-1 bg-gray-200 dark:bg-neutral-700"></span>
                    </div>

                    <div class="bg-white dark:bg-neutral-900 rounded-sm overflow-hidden border border-gray-100 dark:border-neutral-700 shadow-lg">
                        @foreach($patients as $patient)
                        <div class="flex flex-col sm:flex-row justify-between items-center p-5 border-b dark:border-neutral-800 last:border-0 hover:bg-[#C59D5F]/5 dark:hover:bg-neutral-800 transition group">
                            <div class="flex items-center gap-5 text-left w-full sm:w-auto">
                                {{-- Avatar con Iniciales --}}
                                <div class="w-14 h-14 rounded-full bg-[#C59D5F]/10 text-[#C59D5F] flex items-center justify-center font-bold text-lg uppercase shrink-0 border border-[#C59D5F]/20">
                                    {{ substr($patient->name, 0, 2) }}
                                </div>

                                <div>
                                    <p class="font-bold text-xl text-gray-900 dark:text-white font-serif-display group-hover:text-[#C59D5F] transition">
                                        {{ $patient->name }}
                                    </p>
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                            {{ $patient->ci }}
                                        </span>
                                        <span class="text-gray-300 dark:text-neutral-600">|</span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            {{ $patient->age }} años
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('prescriptions.create', $patient->id) }}" class="mt-4 sm:mt-0 w-full sm:w-auto btn-gold font-bold py-2.5 px-6 rounded-sm shadow-md flex items-center justify-center gap-2 transition transform hover:-translate-y-0.5 uppercase text-xs tracking-wider">
                                <span class="">Seleccionar</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    {{-- Estado Vacío --}}
                    <div class="p-8 bg-[#C59D5F]/5 border border-[#C59D5F]/20 rounded-sm text-center">
                        <div class="inline-block p-3 rounded-full bg-[#C59D5F]/10 mb-3">
                            <svg class="w-10 h-10 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white font-serif-display mb-1">No se encontraron resultados</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">No existe ningún paciente registrado con ese nombre o carnet.</p>

                        <a href="{{ route('patients.create') }}" class="inline-flex items-center btn-gold font-bold py-3 px-8 rounded-sm shadow-lg transition uppercase text-xs tracking-wider">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                            Registrar Nuevo Paciente
                        </a>
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app>
