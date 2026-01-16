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

    <div class="max-w-5xl mx-auto bg-white dark:bg-neutral-800 p-8 rounded-sm shadow-xl border-t-4 border-[#C59D5F] transition-colors">

        {{-- ENCABEZADO --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 border-b border-gray-100 dark:border-neutral-700 pb-6">
            <div>
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-[#C59D5F]/10 rounded-full text-[#C59D5F]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">
                        Editar Receta #{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}
                    </h2>
                </div>

                <div class="mt-3 flex items-center gap-4 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Paciente:</span>
                    <span class="font-bold text-lg text-[#C59D5F]">{{ $patient->name }}</span>
                </div>
            </div>

            <a href="{{ route('prescriptions.history', $patient->id) }}" class="mt-4 md:mt-0 text-gray-400 hover:text-[#C59D5F] dark:text-gray-500 dark:hover:text-white flex items-center gap-2 text-xs font-bold uppercase tracking-wider transition group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Historial
            </a>
        </div>

        <form action="{{ route('prescriptions.update', $prescription->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ANAMNESIS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Anamnesis</label>
                    <textarea name="anamnesis" rows="3" class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition text-sm">{{ $prescription->anamnesis }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Antecedentes</label>
                    <textarea name="antecedentes" rows="3" class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition text-sm">{{ $prescription->antecedentes }}</textarea>
                </div>
            </div>

            {{-- SECCIÓN REFRACCIÓN (LEJOS) --}}
            <div class="mb-10 border border-gray-200 dark:border-neutral-700 rounded-sm overflow-hidden">
                <div class="bg-neutral-900 px-4 py-2 border-b border-gray-800 flex items-center justify-between">
                    <span class="text-white text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        Refracción Clínica (Lejos)
                    </span>
                </div>

                <div class="p-6 bg-gray-50 dark:bg-neutral-900/50">
                    {{-- Headers --}}
                    <div class="hidden md:grid grid-cols-7 gap-4 mb-2 text-center">
                        <div class="col-start-2 col-span-2 text-[10px] font-bold text-gray-400 uppercase">Esfera</div>
                        <div class="col-span-2 text-[10px] font-bold text-gray-400 uppercase">Cilindro</div>
                        <div class="col-span-2 text-[10px] font-bold text-gray-400 uppercase">Eje (°)</div>
                    </div>

                    {{-- OD --}}
                    <div class="grid grid-cols-1 md:grid-cols-7 gap-4 items-center mb-3">
                        <div class="flex items-center justify-center md:justify-start gap-2">
                            <span class="font-bold text-lg text-[#C59D5F] font-serif-display">OD</span>
                            <span class="text-[10px] text-gray-400 md:hidden">Ojo Derecho</span>
                        </div>
                        <div class="col-span-2"><input type="number" step="0.25" name="od_esfera" value="{{ $prescription->od_esfera }}" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="od_cilindro" value="{{ $prescription->od_cilindro }}" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                        <div class="col-span-2"><input type="number" name="od_eje" value="{{ $prescription->od_eje }}" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                    </div>

                    {{-- OI --}}
                    <div class="grid grid-cols-1 md:grid-cols-7 gap-4 items-center">
                        <div class="flex items-center justify-center md:justify-start gap-2">
                            <span class="font-bold text-lg text-gray-400 font-serif-display">OI</span>
                            <span class="text-[10px] text-gray-400 md:hidden">Ojo Izquierdo</span>
                        </div>
                        <div class="col-span-2"><input type="number" step="0.25" name="oi_esfera" value="{{ $prescription->oi_esfera }}" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="oi_cilindro" value="{{ $prescription->oi_cilindro }}" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                        <div class="col-span-2"><input type="number" name="oi_eje" value="{{ $prescription->oi_eje }}" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                    </div>
                </div>
            </div>

            {{-- ADICIÓN --}}
            <div class="relative py-6 mb-4">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-200 dark:border-neutral-700"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white dark:bg-neutral-800 px-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Adición (ADD)</span>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 mb-10 justify-center">
                <div class="w-full md:w-1/3 flex items-center">
                    <span class="w-12 font-bold text-[#C59D5F] text-center">OD</span>
                    <input type="number" step="0.25" name="add_od" value="{{ $prescription->add_od }}" placeholder="+0.00" class="flex-1 p-2.5 border border-gray-200 rounded-sm font-mono text-center bg-white dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none">
                </div>
                <div class="w-full md:w-1/3 flex items-center">
                    <span class="w-12 font-bold text-gray-400 text-center">OI</span>
                    <input type="number" step="0.25" name="add_oi" value="{{ $prescription->add_oi }}" placeholder="+0.00" class="flex-1 p-2.5 border border-gray-200 rounded-sm font-mono text-center bg-white dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none">
                </div>
            </div>

            {{-- SECCIÓN CERCA (OPCIONAL) --}}
            <div class="mb-10 border border-gray-200 dark:border-neutral-700 rounded-sm overflow-hidden opacity-90">
                <div class="bg-gray-100 dark:bg-neutral-700 px-4 py-2 border-b border-gray-200 dark:border-neutral-600 flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-300 text-xs font-bold uppercase tracking-widest">
                        Visión Cerca (Opcional)
                    </span>
                </div>
                <div class="p-6 bg-gray-50 dark:bg-neutral-900/30">
                    {{-- OD --}}
                    <div class="grid grid-cols-1 md:grid-cols-7 gap-4 items-center mb-3">
                        <div class="font-bold text-lg text-[#C59D5F] font-serif-display text-center">OD</div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_od_esfera" value="{{ $prescription->cerca_od_esfera }}" placeholder="Esfera" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_od_cilindro" value="{{ $prescription->cerca_od_cilindro }}" placeholder="Cilindro" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                        <div class="col-span-2"><input type="number" name="cerca_od_eje" value="{{ $prescription->cerca_od_eje }}" placeholder="Eje" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                    </div>
                    {{-- OI --}}
                    <div class="grid grid-cols-1 md:grid-cols-7 gap-4 items-center">
                        <div class="font-bold text-lg text-gray-400 font-serif-display text-center">OI</div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_oi_esfera" value="{{ $prescription->cerca_oi_esfera }}" placeholder="Esfera" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_oi_cilindro" value="{{ $prescription->cerca_oi_cilindro }}" placeholder="Cilindro" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                        <div class="col-span-2"><input type="number" name="cerca_oi_eje" value="{{ $prescription->cerca_oi_eje }}" placeholder="Eje" class="w-full p-2.5 text-center border border-gray-200 rounded-sm font-mono bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none"></div>
                    </div>
                </div>
            </div>

            {{-- OTROS DATOS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">DIP</label>
                    <input type="text" name="dip" value="{{ $prescription->dip }}" class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none font-medium text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Diagnóstico</label>
                    <div class="relative">
                        <select name="diagnostico" class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none appearance-none cursor-pointer text-sm font-medium">
                            <option value="Miopía" {{ $prescription->diagnostico == 'Miopía' ? 'selected' : '' }}>Miopía</option>
                            <option value="Hipermetropía" {{ $prescription->diagnostico == 'Hipermetropía' ? 'selected' : '' }}>Hipermetropía</option>
                            <option value="Astigmatismo" {{ $prescription->diagnostico == 'Astigmatismo' ? 'selected' : '' }}>Astigmatismo</option>
                            <option value="Presbicia" {{ $prescription->diagnostico == 'Presbicia' ? 'selected' : '' }}>Presbicia</option>
                            <option value="Miopía con Astigmatismo" {{ $prescription->diagnostico == 'Miopía con Astigmatismo' ? 'selected' : '' }}>Miopía con Astigmatismo</option>
                            <option value="Hipermetropía con Astigmatismo" {{ $prescription->diagnostico == 'Hipermetropía con Astigmatismo' ? 'selected' : '' }}>Hipermetropía con Astigmatismo</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-10">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Observaciones</label>
                <textarea name="observaciones" rows="3" class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition text-sm">{{ $prescription->observaciones }}</textarea>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100 dark:border-neutral-700 gap-4">
                <a href="{{ route('prescriptions.history', $patient->id) }}" class="px-6 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition">
                    Cancelar
                </a>
                <button type="submit" class="btn-gold font-bold py-2.5 px-8 rounded-sm shadow-md transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wider">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</x-app>
