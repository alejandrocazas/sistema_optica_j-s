<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingresar - Grupo Óptico J & S</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Importamos fuentes: Montserrat (Moderna) y Playfair Display (Elegante/Serifa) --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">

    <style>
        /* Color Dorado Personalizado del Logo */
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .ring-gold:focus { --tw-ring-color: #C59D5F; }

        /* Gradiente Dorado para el Botón */
        .btn-gold {
            background: linear-gradient(135deg, #C59D5F 0%, #a37f45 100%);
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d6ad6d 0%, #b89050 100%);
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">

    <div class="min-h-screen flex">

        {{-- PANEL IZQUIERDO (Identidad de Marca - Fondo Oscuro Luxury) --}}
        <div class="hidden lg:flex w-1/2 bg-neutral-900 relative items-center justify-center overflow-hidden">
            {{-- Capa de oscurecimiento elegante --}}
            <div class="absolute top-0 left-0 w-full h-full bg-black opacity-40 z-10"></div>

            {{-- Formas decorativas Doradas (Abstractas) --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-[#C59D5F] opacity-10 blur-3xl z-0"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full bg-[#C59D5F] opacity-10 blur-3xl z-0"></div>

            {{-- Imagen de fondo (Opcional: puedes poner una foto de lentes o consultorio en B&N) --}}
            <img src="https://images.unsplash.com/photo-1511499767150-a48a237f0083?q=80&w=2080&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-20 z-0 grayscale">

            <div class="relative z-20 text-white text-center px-10">

                {{-- LOGO GRANDE (Si tienes la imagen, descomenta la línea de abajo y borra el texto) --}}
                {{-- <img src="{{ asset('images/logo_js.png') }}" class="w-48 mx-auto mb-6 drop-shadow-2xl"> --}}

                {{-- Representación del Logo en CSS (Mientras subes la imagen) --}}
                <div class="mb-8 flex flex-col justify-center items-center">
                    <div class="w-24 h-24 rounded-full border-2 border-[#C59D5F] flex items-center justify-center mb-4 shadow-[0_0_15px_rgba(197,157,95,0.3)]">
                        <span class="text-5xl font-serif italic text-white" style="font-family: 'Playfair Display', serif;">J<span class="text-[#C59D5F]">&</span>S</span>
                    </div>
                    <h1 class="text-4xl tracking-widest uppercase font-light mt-2" style="font-family: 'Montserrat', sans-serif;">
                        GRUPO ÓPTICO
                    </h1>
                    <p class="text-[#C59D5F] tracking-[0.5em] text-sm mt-2 font-semibold">VISIÓN Y VIDA</p>
                </div>

                <div class="w-16 h-1 bg-[#C59D5F] mx-auto rounded-full mb-6"></div>
                <p class="text-gray-300 text-lg font-light italic">"Excelencia visual con estilo y precisión"</p>
            </div>
        </div>

        {{-- PANEL DERECHO (Formulario Limpio) --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8 relative">

            {{-- Decoración sutil esquina --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-bl-[100px] opacity-50"></div>

            <div class="w-full max-w-md z-10">

                <div class="mb-12 text-center lg:text-left">
                    {{-- Logo versión móvil / tablet --}}
                    <div class="lg:hidden mb-6 flex justify-center">
                         <span class="text-3xl font-serif italic text-gray-900 border-2 border-[#C59D5F] rounded-full w-16 h-16 flex items-center justify-center">J<span class="text-[#C59D5F]">&</span>S</span>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-800 mb-2" style="font-family: 'Playfair Display', serif;">Bienvenido</h2>
                    <p class="text-gray-500 text-sm">Ingresa tus credenciales para acceder al sistema.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Correo Electrónico</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#C59D5F] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </span>
                            <input type="email" name="email" required autofocus
                                class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 text-gray-800 rounded-sm focus:bg-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] transition outline-none font-medium placeholder-gray-300"
                                placeholder="usuario@grupojs.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-[#C59D5F]" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Contraseña</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#C59D5F] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </span>
                            <input type="password" name="password" required
                                class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 text-gray-800 rounded-sm focus:bg-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] transition outline-none font-medium placeholder-gray-300"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-[#C59D5F]" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#C59D5F] shadow-sm focus:ring-[#C59D5F]">
                            <span class="ml-2 text-sm text-gray-600">Recuérdame</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-gray-500 hover:text-[#C59D5F] transition-colors font-medium underline decoration-transparent hover:decoration-[#C59D5F]" href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full btn-gold text-white font-bold py-3.5 rounded-sm shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 active:scale-95 flex justify-center tracking-widest uppercase text-xs border border-transparent">
                        Ingresar al Sistema
                    </button>
                </form>

                <p class="mt-10 text-center text-xs text-gray-400">
                    © {{ date('Y') }} Grupo Óptico J & S. Oruro - Bolivia.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
