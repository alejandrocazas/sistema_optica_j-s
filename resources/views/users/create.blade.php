<x-app>
    {{-- Estilos para esta vista --}}
    <style>
        .text-gold { color: #C59D5F; }
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

    <div class="max-w-2xl mx-auto py-10 sm:px-6 lg:px-8">

        {{-- ENCABEZADO --}}
        <div class="mb-8 flex items-center gap-3">
            <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Registrar Nuevo Empleado</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Crea las credenciales de acceso y asigna sucursal.</p>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F] transition-colors p-8">

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                {{-- NOMBRE --}}
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre Completo</label>
                    <input type="text" name="name" required placeholder="Ej: Ana Gomez"
                        class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                </div>

                {{-- EMAIL --}}
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Correo Electrónico (Login)</label>
                    <input type="email" name="email" required placeholder="ana@optica.com"
                        class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- SUCURSAL --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Sucursal Asignada</label>
                        <div class="relative">
                            <select name="branch_id" required
                                class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none appearance-none transition cursor-pointer font-medium">
                                <option value="" disabled selected>Seleccione una sucursal...</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1 italic">El usuario solo verá datos de esta sucursal.</p>
                    </div>

                    {{-- ROL --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Rol / Cargo</label>
                        <div class="relative">
                            <select name="role"
                                class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none appearance-none transition cursor-pointer font-medium">
                                <option value="vendedor">Vendedor (Caja y Ventas)</option>
                                <option value="optometrista">Optometrista (Pacientes y Recetas)</option>
                                <option value="admin">Administrador (Acceso Total)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Separador con texto --}}
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-200 dark:border-neutral-700"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white dark:bg-neutral-800 px-3 text-gray-400 text-xs font-serif-display italic tracking-wide">Credenciales de Acceso</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- PASSWORD --}}
                    <div>
                        <label class="block text-xs font-bold text-[#C59D5F] uppercase tracking-wider mb-2">Contraseña</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                    </div>

                    {{-- CONFIRMAR PASSWORD --}}
                    <div>
                        <label class="block text-xs font-bold text-[#C59D5F] uppercase tracking-wider mb-2">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                            class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none transition font-medium">
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="flex justify-end gap-4 border-t border-gray-100 dark:border-neutral-700 pt-6">
                    <a href="{{ route('users.index') }}"
                       class="px-6 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition">
                        Cancelar
                    </a>

                    <button type="submit" class="btn-gold font-bold py-2.5 px-8 rounded-sm shadow-md flex items-center gap-2 transform transition hover:-translate-y-0.5 text-sm uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app>
