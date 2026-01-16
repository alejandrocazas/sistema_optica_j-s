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

    <div class="max-w-6xl mx-auto">

        {{-- TARJETA DE PACIENTE --}}
        <div class="bg-white dark:bg-neutral-800 p-8 rounded-sm shadow-xl mb-10 flex flex-col md:flex-row justify-between items-center gap-6 border-l-4 border-[#C59D5F] transition-colors relative overflow-hidden">
            {{-- Fondo decorativo sutil --}}
            <div class="absolute right-0 top-0 h-full w-32 bg-gradient-to-l from-gray-50 to-transparent dark:from-white/5 pointer-events-none"></div>

            <div class="relative z-10 w-full">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">
                        {{ $patient->name }}
                    </h1>
                    <a href="{{ route('patients.edit', $patient->id) }}" class="text-gray-400 hover:text-[#C59D5F] transition" title="Editar Datos Personales">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                </div>

                <div class="flex flex-wrap gap-3 text-sm font-medium">
                    <span class="bg-neutral-100 dark:bg-neutral-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-sm border border-gray-200 dark:border-neutral-600 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        CI: {{ $patient->ci }}
                    </span>
                    <span class="bg-neutral-100 dark:bg-neutral-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-sm border border-gray-200 dark:border-neutral-600 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $patient->age }} años
                    </span>
                    <span class="bg-neutral-100 dark:bg-neutral-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-sm border border-gray-200 dark:border-neutral-600 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        {{ $patient->phone ?? 'S/T' }}
                    </span>
                </div>
            </div>

            <div class="relative z-10 w-full md:w-auto shrink-0">
                <a href="{{ route('prescriptions.create', $patient->id) }}" class="btn-gold font-bold py-3 px-8 rounded-sm shadow-lg flex items-center justify-center gap-2 transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wider w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Consulta
                </a>
            </div>
        </div>

        <div class="flex items-center justify-between mb-4 px-1">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white font-serif-display">Historial Médico</h2>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $prescriptions->count() }} registros</span>
        </div>

        @if($prescriptions->isEmpty())
            <div class="bg-white dark:bg-neutral-800 p-12 rounded-sm shadow-md text-center border-t-4 border-gray-200 dark:border-neutral-700">
                <div class="inline-block p-4 rounded-full bg-gray-50 dark:bg-neutral-900 mb-3">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <p class="font-bold text-lg text-gray-600 dark:text-gray-300 font-serif-display">Sin historial clínico</p>
                <p class="text-gray-400 text-sm mt-1">Este paciente aún no tiene consultas registradas.</p>
            </div>
        @else
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F] transition-colors">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-neutral-900 text-white text-xs uppercase tracking-widest border-b border-gray-200 dark:border-neutral-700">
                                <th class="px-6 py-4 text-left font-bold">Fecha</th>
                                <th class="px-6 py-4 text-left font-bold">Diagnóstico</th>
                                <th class="px-6 py-4 text-left font-bold">Especialista</th>
                                <th class="px-6 py-4 text-center font-bold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-neutral-700 text-gray-700 dark:text-gray-300">
                            @foreach($prescriptions as $recipe)
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50 transition bg-white dark:bg-neutral-800 group">

                                {{-- FECHA --}}
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-[#C59D5F]/10 rounded-sm text-[#C59D5F]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white font-mono text-sm">
                                                {{ $recipe->created_at->format('d/m/Y') }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $recipe->created_at->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- DIAGNÓSTICO --}}
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-block bg-[#C59D5F]/10 text-[#C59D5F] text-[10px] px-2 py-1 rounded-sm font-bold uppercase tracking-wide border border-[#C59D5F]/20 mb-1">
                                        {{ $recipe->diagnostico ?? 'General' }}
                                    </span>
                                    @if($recipe->observaciones)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 italic truncate w-64 border-l-2 border-gray-200 dark:border-neutral-600 pl-2">
                                            {{ $recipe->observaciones }}
                                        </p>
                                    @endif
                                </td>

                                {{-- ESPECIALISTA --}}
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center text-[10px] font-bold text-gray-500 border border-gray-200 dark:border-neutral-600">
                                            {{ substr($recipe->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-gray-700 dark:text-gray-300 font-medium text-xs">
                                            {{ $recipe->user->name }}
                                        </span>
                                    </div>
                                </td>

                                {{-- ACCIONES --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2">

                                        {{-- Imprimir --}}
                                        <a href="{{ route('prescriptions.print', $recipe->id) }}" target="_blank" class="p-2 text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-full transition" title="Imprimir Receta">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                        </a>

                                        {{-- Editar --}}
                                        <a href="{{ route('prescriptions.edit', $recipe->id) }}" class="p-2 text-gray-400 hover:text-[#C59D5F] hover:bg-[#C59D5F]/10 rounded-full transition" title="Editar Receta">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Eliminar --}}
                                        <form action="{{ route('prescriptions.destroy', $recipe->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta receta permanentemente?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition" title="Eliminar del Historial">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Verificamos si existe la variable de sesión para imprimir
        @if(session('print_prescription_id'))
            // Generamos la URL de impresión
            const printUrl = "{{ route('prescriptions.print', session('print_prescription_id')) }}";
            // Abrimos en una nueva pestaña
            window.open(printUrl, '_blank');
        @endif
    });
</script>
</x-app>
