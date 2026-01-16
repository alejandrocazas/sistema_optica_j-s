<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Acceso - Grupo Óptico J & S</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fuentes: Montserrat y Playfair Display --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <style>
        /* Clases de color dorado personalizado */
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }

        /* Botón con degradado dorado Premium */
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

        {{-- PANEL IZQUIERDO (Identidad de Marca Oscura) --}}
        <div class="hidden lg:flex w-1/2 bg-neutral-900 relative items-center justify-center overflow-hidden">
            {{-- Capa de oscurecimiento --}}
            <div class="absolute top-0 left-0 w-full h-full bg-black opacity-30 z-10"></div>

            {{-- Destellos dorados de fondo --}}
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-[#C59D5F] opacity-10 blur-3xl z-0"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full bg-[#C59D5F] opacity-5 blur-3xl z-0"></div>

            <div class="relative z-20 text-white text-center px-12">
                {{-- Icono de Candado Estilizado en Dorado --}}
                <div class="w-20 h-20 mx-auto mb-6 rounded-full border border-[#C59D5F]/30 flex items-center justify-center bg-[#C59D5F]/10 backdrop-blur-sm">
                    <svg class="w-10 h-10 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>

                <h2 class="text-3xl font-bold mb-3 font-serif-display tracking-wide">Recuperación de Seguridad</h2>
                <p class="text-gray-300 font-light text-sm leading-relaxed">
                    Para proteger la información de nuestros pacientes, <br>sigue el proceso seguro de restablecimiento.
                </p>
            </div>
        </div>

        {{-- PANEL DERECHO (Formulario Limpio) --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white relative">
            {{-- Decoración sutil superior derecha --}}
            <div class="absolute top-0 right-0 w-24 h-24 bg-gray-50 rounded-bl-[80px] opacity-60"></div>

            <div class="w-full max-w-md z-10">
                <div class="mb-8">
                    <h3 class="text-3xl font-bold text-gray-900 font-serif-display mb-2">¿Olvidaste tu contraseña?</h3>
                    <p class="text-sm text-gray-500 font-medium">Ingresa tu correo electrónico asociado a la cuenta.</p>
                </div>

                {{-- Mensaje de estado (Personalizado al tono dorado/suave) --}}
                @if (session('status'))
                    <div class="mb-6 bg-[#C59D5F]/10 border-l-4 border-[#C59D5F] text-gray-700 p-4 text-sm font-medium" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Correo Electrónico</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#C59D5F] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </span>
                            <input type="email" name="email" required autofocus
                                class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-sm focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none transition text-gray-700 font-medium placeholder-gray-300"
                                placeholder="usuario@grupojs.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-[#C59D5F]" />
                    </div>

                    <div class="flex items-center justify-end pt-2">
                        <button type="submit" class="w-full btn-gold text-white font-bold py-3.5 rounded-sm shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 active:scale-95 tracking-widest uppercase text-xs">
                            Enviar Enlace de Recuperación
                        </button>
                    </div>

                    <div class="text-center mt-8">
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-[#C59D5F] transition font-semibold group">
                            <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Volver al inicio de sesión
                        </a>
                    </div>

                    <p class="mt-10 text-center text-xs text-gray-300">
                        © {{ date('Y') }} Grupo Óptico J & S
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
