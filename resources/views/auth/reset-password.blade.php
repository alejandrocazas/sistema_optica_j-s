<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Contraseña - Grupo Óptico J & S</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fuentes: Montserrat y Playfair Display --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }

        .btn-gold {
            background: linear-gradient(135deg, #C59D5F 0%, #a37f45 100%);
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d6ad6d 0%, #b89050 100%);
        }

        .font-serif-display { font-family: 'Playfair Display', serif; }
        .font-sans-body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="font-sans-body antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen flex">

        {{-- PANEL IZQUIERDO (Identidad de Marca) --}}
        <div class="hidden lg:flex w-1/2 bg-neutral-900 relative items-center justify-center overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-black opacity-30 z-10"></div>

            {{-- Destellos dorados --}}
            <div class="absolute -top-10 -left-10 w-96 h-96 rounded-full bg-[#C59D5F] opacity-5 blur-3xl z-0"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full bg-[#C59D5F] opacity-10 blur-3xl z-0"></div>

            <div class="relative z-20 text-white text-center px-12">
                {{-- Icono Check/Candado Dorado --}}
                <div class="w-20 h-20 mx-auto mb-6 rounded-full border border-[#C59D5F]/30 flex items-center justify-center bg-[#C59D5F]/10 backdrop-blur-sm">
                    <svg class="w-10 h-10 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>

                <h2 class="text-3xl font-bold mb-3 font-serif-display tracking-wide">Restablecer Acceso</h2>
                <p class="text-gray-300 font-light text-sm leading-relaxed">
                    Crea una contraseña robusta para mantener<br>la seguridad de tu cuenta.
                </p>
            </div>
        </div>

        {{-- PANEL DERECHO (Formulario) --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white relative">
            <div class="w-full max-w-md z-10">
                <div class="mb-10">
                    <h3 class="text-3xl font-bold text-gray-900 font-serif-display mb-2">Nueva Contraseña</h3>
                    <p class="text-sm text-gray-500 font-medium">Por favor, ingresa y confirma tu nueva clave de acceso.</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Correo (Readonly) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Correo Electrónico</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </span>
                            <input type="email" name="email" value="{{ old('email', $request->email) }}" required readonly
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-sm text-gray-500 cursor-not-allowed font-medium select-none" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-[#C59D5F]" />
                    </div>

                    {{-- Nueva Contraseña --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nueva Contraseña</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#C59D5F] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </span>
                            <input type="password" name="password" required autocomplete="new-password" autofocus
                                class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-sm focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none transition text-gray-800 font-medium placeholder-gray-300"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-[#C59D5F]" />
                    </div>

                    {{-- Confirmar Contraseña --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Confirmar Contraseña</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#C59D5F] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            <input type="password" name="password_confirmation" required autocomplete="new-password"
                                class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-sm focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none transition text-gray-800 font-medium placeholder-gray-300"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-[#C59D5F]" />
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full btn-gold text-white font-bold py-3.5 rounded-sm shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 active:scale-95 tracking-widest uppercase text-xs">
                            Actualizar Contraseña
                        </button>
                    </div>
                </form>

                <p class="mt-10 text-center text-xs text-gray-300">
                    © {{ date('Y') }} Grupo Óptico J & S
                </p>
            </div>
        </div>
    </div>
</body>
</html>
