<x-app>
    {{-- Estilos para esta vista --}}
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

    <div class="max-w-4xl mx-auto bg-white dark:bg-neutral-800 p-8 rounded-sm shadow-xl border-t-4 border-[#C59D5F] transition-colors">

        {{-- ENCABEZADO --}}
        <div class="flex justify-between items-start mb-8 border-b border-gray-100 dark:border-neutral-700 pb-6">
            <div class="flex gap-4">
                <div class="p-3 bg-[#C59D5F]/10 rounded-full text-[#C59D5F]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">
                        Registrar Nuevo Paciente
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Ingresa los datos personales para crear el historial clínico.
                    </p>
                </div>
            </div>

            <a href="{{ route('patients.index') }}" class="text-gray-400 hover:text-[#C59D5F] dark:text-gray-500 dark:hover:text-white flex items-center gap-2 text-xs font-bold uppercase tracking-wider transition group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver
            </a>
        </div>

        <form action="{{ route('patients.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

                {{-- Nombre Completo --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre Completo</label>
                    <input type="text" name="name" required placeholder="Ej: Juan Pérez"
                        class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                </div>

                {{-- CI / DNI --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">CI / DNI</label>
                    <input type="text" name="ci" placeholder="Ej: 1234567"
                        class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                </div>

                {{-- Edad --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Edad</label>
                    <input type="number" name="age" placeholder="Ej: 30"
                        class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                </div>

                {{-- Celular --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Celular / WhatsApp</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </span>
                        <input type="text" name="phone" placeholder="70000000"
                            class="w-full py-3 pl-10 pr-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                    </div>
                </div>

                {{-- Ocupación --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Ocupación</label>
                    <input type="text" name="occupation" placeholder="Ej: Estudiante, Arquitecto..."
                        class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-6 rounded-sm text-sm">
                    <strong class="font-bold flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Atención:
                    </strong>
                    <ul class="list-disc list-inside ml-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex justify-end gap-4 pt-6 border-t border-gray-100 dark:border-neutral-700">
                <a href="{{ route('patients.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition">
                    Cancelar
                </a>
                <button type="submit" class="btn-gold font-bold py-2.5 px-8 rounded-sm shadow-md flex items-center gap-2 transform transition hover:-translate-y-0.5 text-sm uppercase tracking-wider">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Guardar Paciente
                </button>
            </div>
        </form>
    </div>
</x-app>
