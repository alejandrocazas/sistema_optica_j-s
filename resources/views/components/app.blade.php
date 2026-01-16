<!DOCTYPE html>
<html lang="es" class="h-full"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Grupo Óptico J & S') }}</title>

    {{-- Importamos Fuentes y Estilos --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .font-sans-body { font-family: 'Montserrat', sans-serif; }
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
    </style>
</head>
<body class="h-full font-sans-body antialiased text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900" x-data="{ sidebarOpen: false }">

    {{-- Overlay para móviles --}}
    <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-neutral-900/80 backdrop-blur-sm" @click="sidebarOpen = false"></div>
        <div class="fixed inset-0 flex">
            <div class="relative mr-16 flex w-full max-w-xs flex-1">
                @include('layouts.sidebar')
            </div>
        </div>
    </div>

    {{-- Sidebar Estático (Desktop) --}}
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
        @include('layouts.sidebar')
    </div>

    {{-- Contenido Principal --}}
    <div class="lg:pl-64 flex flex-col min-h-screen transition-colors duration-300">

        {{-- HEADER SUPERIOR: BLANCO CON BORDE DORADO --}}
        <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-[#C59D5F] bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 text-gray-800 dark:bg-neutral-900 dark:text-white dark:border-gray-800 transition-colors duration-300">

            {{-- Botón Hamburguesa (Móvil) --}}
            <button type="button" class="-m-2.5 p-2.5 text-gray-500 hover:text-[#C59D5F] lg:hidden" @click="sidebarOpen = true">
                <span class="sr-only">Abrir menú</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
            </button>

            {{-- Separador Vertical --}}
            <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 lg:hidden" aria-hidden="true"></div>

            <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 justify-between items-center">

                {{-- Título de la Sección --}}
                <div class="flex items-center">
                    <span class="w-1.5 h-6 bg-[#C59D5F] rounded-full mr-3 hidden sm:block"></span>
                    <h2 class="text-lg font-bold tracking-wide text-gray-800 dark:text-white font-serif-display">
                        Sistema de Gestión
                    </h2>
                </div>

                <div class="flex items-center gap-x-4 lg:gap-x-6">

                    {{-- Botón Dark Mode --}}
                    <button @click="darkMode = !darkMode" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition text-[#C59D5F]">
                        <svg x-show="darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                        <svg x-show="!darkMode" class="h-6 w-6 text-gray-500 hover:text-[#C59D5F]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                    </button>

                    {{-- Dropdown de Usuario --}}
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" class="-m-1.5 flex items-center p-1.5 rounded-full hover:bg-gray-50 dark:hover:bg-gray-800 transition border border-transparent hover:border-[#C59D5F]/30" @click="open = !open" @click.away="open = false">
                            <div class="flex items-center">
                                @if(auth()->user()->profile_photo_path)
                                    <img class="h-9 w-9 rounded-full object-cover border-2 border-[#C59D5F]" src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="">
                                @else
                                    <img class="h-9 w-9 rounded-full border-2 border-[#C59D5F]" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=171717&color=C59D5F" alt="">
                                @endif
                                <span class="hidden lg:flex lg:items-center">
                                    <div class="ml-3 text-left">
                                        <p class="text-sm font-bold text-gray-800 dark:text-white leading-none">{{ auth()->user()->name }}</p>
                                        <p class="text-[10px] text-[#C59D5F] font-semibold uppercase mt-0.5">{{ auth()->user()->role ?? 'Usuario' }}</p>
                                    </div>
                                    <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                </span>
                            </div>
                        </button>

                        <div x-cloak x-show="open" x-transition class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-md bg-white dark:bg-neutral-800 py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none border-t-2 border-[#C59D5F]">
                            <a href="{{ route('profile.edit') }}" class="w-full flex px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 items-center gap-2 hover:text-[#C59D5F]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Mi Perfil
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 items-center gap-2 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <main class="py-10 flex-1">
            <div class="px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
    <script>
        // === CONFIGURACIÓN DE ALERTAS DE MARCA ===
        const brandColor = '#C59D5F'; // Dorado J&S

        // A. ALERTAS DE ÉXITO
        @if (session('success'))
            Swal.fire({
                title: '¡Excelente!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: brandColor,
                confirmButtonText: 'Aceptar',
                timer: 3000
            });
        @endif

        // B. ALERTAS DE ERROR
        @if (session('error'))
            Swal.fire({
                title: 'Error',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Entendido'
            });
        @endif

        // C. FUNCIÓN PARA BORRAR (Confirmación)
        function confirmDelete(event, formId) {
            event.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: brandColor, // Dorado
                cancelButtonColor: '#6B7280',   // Gris
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
</body>
</html>
