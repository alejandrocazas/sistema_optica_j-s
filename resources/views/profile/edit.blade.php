<x-app>
    {{-- Estilos específicos para esta vista --}}
    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .focus-ring-gold:focus { --tw-ring-color: #C59D5F; border-color: #C59D5F; }
        .font-serif-display { font-family: 'Playfair Display', serif; }

        /* Botón Dorado Premium */
        .btn-gold {
            background: linear-gradient(135deg, #C59D5F 0%, #a37f45 100%);
            color: white;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d6ad6d 0%, #b89050 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(197, 157, 95, 0.4);
        }
    </style>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        {{-- Encabezado con Tipografía Elegante --}}
        <div class="mb-8 flex items-center gap-3">
            <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Mi Perfil</h1>
        </div>

        <div class="bg-white dark:bg-neutral-800 shadow-xl overflow-hidden sm:rounded-lg p-8 border-t-4 border-[#C59D5F] transition-colors">

            {{-- Formulario Único --}}
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- FOTO DE PERFIL --}}
                <div class="mb-10 text-center">
                    <div class="relative inline-block group">
                        <div class="mt-2 p-1 border-2 border-[#C59D5F] rounded-full border-dashed">
                            <img id="preview-image"
                                 class="h-32 w-32 rounded-full object-cover mx-auto shadow-lg border-4 border-white dark:border-neutral-700"
                                 src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=C59D5F&background=171717' }}"
                                 alt="{{ $user->name }}">
                        </div>

                        <label class="absolute bottom-2 right-2 bg-[#C59D5F] hover:bg-[#a37f45] text-white p-2.5 rounded-full shadow-lg cursor-pointer transition transform hover:scale-110 border-2 border-white dark:border-neutral-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                            <input type="file" name="photo" class="hidden" onchange="previewFile(this)" accept="image/*" />
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 uppercase tracking-wide">Click en la cámara para cambiar</p>
                    @error('photo') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- DATOS PERSONALES --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Nombre Completo</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full pl-10 p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-ring-gold focus:border-[#C59D5F] outline-none transition font-medium">
                        </div>
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Correo Electrónico</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </span>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full pl-10 p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-ring-gold focus:border-[#C59D5F] outline-none transition font-medium">
                        </div>
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="relative my-10">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-200 dark:border-neutral-700"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white dark:bg-neutral-800 px-3 text-gray-400 text-sm font-serif-display italic">Seguridad de la Cuenta</span>
                    </div>
                </div>

                {{-- SEGURIDAD --}}
                <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white flex items-center gap-2 font-serif-display">
                    <div class="p-1.5 bg-[#C59D5F]/10 rounded-full text-[#C59D5F]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2.166 10.3c.769-2.985 3.836-5.549 8.179-5.549 4.344 0 7.41 2.564 8.179 5.549.082.318-.16.651-.488.651H2.654c-.328 0-.57-.333-.488-.651zM10.335 12a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" /><path d="M4 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z" /></svg>
                    </div>
                    Actualizar Contraseña
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 ml-9">Deja estos campos vacíos si deseas mantener tu contraseña actual.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div>
                        <label class="block font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Contraseña Actual</label>
                        <input type="password" name="current_password"
                            class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-ring-gold focus:border-[#C59D5F] outline-none transition" placeholder="••••••••">
                        @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-xs text-[#C59D5F] uppercase tracking-wider mb-2">Nueva Contraseña</label>
                        <input type="password" name="new_password"
                            class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-ring-gold focus:border-[#C59D5F] outline-none transition" placeholder="••••••••">
                        @error('new_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-xs text-[#C59D5F] uppercase tracking-wider mb-2">Confirmar Nueva</label>
                        <input type="password" name="new_password_confirmation"
                            class="w-full p-3 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-ring-gold focus:border-[#C59D5F] outline-none transition" placeholder="••••••••">
                    </div>
                </div>

                {{-- BOTONES DE ACCIÓN --}}
                <div class="flex justify-end items-center gap-4 border-t border-gray-100 dark:border-neutral-700 pt-6">

                    {{-- 1. BOTÓN CANCELAR --}}
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white font-semibold text-sm py-3 px-6 transition flex items-center gap-2">
                        Cancelar
                    </a>

                    {{-- 2. BOTÓN GUARDAR (PREMIUM GOLD) --}}
                    <button type="submit" class="btn-gold font-bold py-3 px-8 rounded-sm shadow-md transition flex items-center gap-2 text-sm tracking-widest uppercase">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script para previsualizar --}}
    <script>
        function previewFile(input) {
            var file = input.files[0];
            if(file){
                var reader = new FileReader();
                reader.onload = function(){
                    var output = document.getElementById('preview-image');
                    output.src = reader.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app>
