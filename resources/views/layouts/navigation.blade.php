<nav x-data="{ open: false }" class="bg-neutral-900 border-b border-[#C59D5F] sticky top-0 z-50">

    {{-- Estilos para el color dorado (Por seguridad) --}}
    <style>
        .text-gold { color: #C59D5F; }
        .hover-gold:hover { color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        {{-- Icono Simplificado --}}
                        <div class="w-9 h-9 rounded-full border border-[#C59D5F] flex items-center justify-center text-[#C59D5F] bg-[#C59D5F]/10 group-hover:bg-[#C59D5F] group-hover:text-neutral-900 transition duration-300">
                            <span class="font-serif font-bold italic text-sm">JS</span>
                        </div>
                        {{-- Texto solo en desktop --}}
                        <div class="hidden md:block ml-3">
                            <h1 class="text-white font-serif-display font-bold tracking-wider text-sm leading-tight">GRUPO ÓPTICO</h1>
                            <p class="text-[#C59D5F] text-[10px] tracking-[0.2em] uppercase">Visión y Vida</p>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="text-gray-300 hover:text-[#C59D5F] hover:border-[#C59D5F] focus:text-[#C59D5F] focus:border-[#C59D5F] transition duration-150 ease-in-out">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Puedes agregar más enlaces aquí si los necesitas en el futuro --}}
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-neutral-900 hover:text-[#C59D5F] focus:outline-none transition ease-in-out duration-150 group">
                            <div class="group-hover:translate-x-1 transition-transform duration-300">{{ Auth::user()->name }}</div>

                            <div class="ms-1 group-hover:rotate-180 transition-transform duration-300">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="border-t-2 border-[#C59D5F]">
                            <x-dropdown-link :href="route('profile.edit')" class="hover:text-[#C59D5F] hover:bg-gray-50">
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();"
                                        class="hover:text-red-600 hover:bg-red-50">
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-[#C59D5F] hover:bg-neutral-800 focus:outline-none focus:bg-neutral-800 focus:text-[#C59D5F] transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-neutral-900 border-b border-gray-800">
        <div class="pt-2 pb-3 space-y-1">
            {{-- Enlaces Móviles con estilo oscuro --}}
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="text-gray-300 hover:text-[#C59D5F] hover:bg-neutral-800 hover:border-[#C59D5F] focus:text-[#C59D5F] focus:bg-neutral-800 focus:border-[#C59D5F]">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-700 bg-neutral-800/50">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-[#C59D5F]">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-400 hover:text-[#C59D5F] hover:bg-neutral-800">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-gray-400 hover:text-red-400 hover:bg-red-900/20">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
