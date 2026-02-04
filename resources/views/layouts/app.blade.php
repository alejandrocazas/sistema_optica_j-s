<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        [x-cloak] { display: none !important; }
    </style>
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-gray-900 bg-gray-50 dark:bg-neutral-900" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">

    <div class="flex h-screen overflow-hidden">

        <aside class="hidden md:flex md:w-72 md:flex-col fixed inset-y-0 z-50 bg-neutral-900 border-r border-gray-800">
            @include('components.sidebar')
        </aside>

        <div class="flex flex-col flex-1 md:pl-72 transition-all duration-300">

            <header class="flex items-center justify-between h-20 px-6 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm z-40">

                <div class="flex items-center">
                    <div class="w-1 h-8 bg-[#C59D5F] rounded-full mr-4"></div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white font-serif-display tracking-wide">
                        Sistema de Gestión
                    </h2>
                </div>

                <div class="flex items-center gap-6">

                    <button @click="darkMode = !darkMode" class="text-gray-500 hover:text-[#C59D5F] transition focus:outline-none">
                        <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg x-show="darkMode" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-3 focus:outline-none group">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-[#C59D5F] transition">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider">{{ Auth::user()->role ?? 'Admin' }}</p>
                            </div>
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_url)
                                <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-100 group-hover:border-[#C59D5F] transition" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold border-2 border-gray-100 group-hover:border-[#C59D5F] transition">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 border border-gray-100 dark:border-gray-700 z-50" style="display: none;">

                            {{-- CORRECCIÓN AQUÍ: Verificamos si la ruta existe antes de poner el link --}}
                            <a href="{{ Route::has('profile.show') ? route('profile.show') : '#' }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-[#C59D5F]">
                                Perfil
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Cerrar Sesión</button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-neutral-900 p-6">
                {{ $slot }}
            </main>

        </div>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
