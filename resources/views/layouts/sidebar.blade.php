<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-neutral-900 border-r border-gray-800 px-6 pb-4 h-full">

    {{-- Estilos para el color dorado --}}
    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .hover-text-gold:hover { color: #C59D5F; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>

    {{-- LOGO DEL SISTEMA EN SIDEBAR --}}
    <div class="flex h-20 shrink-0 items-center justify-center border-b border-gray-800 mb-2">
        <div class="text-center">
            <h1 class="text-xl font-bold text-white font-serif-display tracking-widest leading-none">
                OPTICA <span class="text-[#C59D5F]">J&S</span>
            </h1>
            <p class="text-[9px] text-gray-500 uppercase tracking-[0.3em] mt-1">Visión y Vida</p>
        </div>
    </div>

    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">

                    {{-- DASHBOARD --}}
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="{{ request()->routeIs('dashboard') ? 'bg-neutral-800 text-[#C59D5F] border-l-2 border-[#C59D5F]' : 'text-gray-400 hover:text-white hover:bg-neutral-800' }} group flex gap-x-3 rounded-r-md p-2 pl-3 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-[#C59D5F]' : 'text-gray-500 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                            Inicio
                        </a>
                    </li>

                    {{-- VENTAS --}}
                    @if(in_array(auth()->user()->role, ['admin', 'vendedor']))
                    <li>
                        <a href="{{ route('sales.index') }}"
                           class="{{ request()->routeIs('sales.*') ? 'bg-neutral-800 text-[#C59D5F] border-l-2 border-[#C59D5F]' : 'text-gray-400 hover:text-white hover:bg-neutral-800' }} group flex gap-x-3 rounded-r-md p-2 pl-3 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('sales.*') ? 'text-[#C59D5F]' : 'text-gray-500 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                            Ventas y Pedidos
                        </a>
                    </li>
                    @endif

                    {{-- PACIENTES (Dropdown) --}}
                    <li x-data="{ open: {{ request()->routeIs('patients.*') || request()->routeIs('prescriptions.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" type="button"
                                class="text-gray-400 hover:text-white hover:bg-neutral-800 flex w-full items-center gap-x-3 rounded-r-md p-2 pl-3 text-sm leading-6 font-semibold text-left transition-all duration-200">
                            <svg class="h-5 w-5 shrink-0 text-gray-500 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                            Pacientes y Atención
                            <svg :class="open ? 'rotate-90 text-[#C59D5F]' : 'text-gray-600'" class="ml-auto h-4 w-4 shrink-0 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        </button>
                        <ul x-show="open" x-cloak class="mt-1 px-2 space-y-1 border-l border-gray-800 ml-4">
                            <li>
                                <a href="/patients"
                                   class="{{ request()->routeIs('patients.*') ? 'text-[#C59D5F] font-bold' : 'text-gray-500 hover:text-gray-300' }} block rounded-md py-2 pl-4 text-xs leading-6 transition-colors">
                                    Directorio Pacientes
                                </a>
                            </li>
                            @if(in_array(auth()->user()->role, ['admin', 'optometrista']))
                            <li>
                                <a href="{{ route('prescriptions.index') }}"
                                   class="{{ request()->routeIs('prescriptions.*') ? 'text-[#C59D5F] font-bold' : 'text-gray-500 hover:text-gray-300' }} block rounded-md py-2 pl-4 text-xs leading-6 transition-colors">
                                    Historial y Consultas
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>

                    {{-- INVENTARIO (Dropdown) --}}
                    @if(auth()->user()->role === 'admin')
                    <li x-data="{ open: {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('purchases.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" type="button"
                                class="text-gray-400 hover:text-white hover:bg-neutral-800 flex w-full items-center gap-x-3 rounded-r-md p-2 pl-3 text-sm leading-6 font-semibold text-left transition-all duration-200">
                           <svg class="h-5 w-5 shrink-0 text-gray-500 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                            Gestión Inventario
                            <svg :class="open ? 'rotate-90 text-[#C59D5F]' : 'text-gray-600'" class="ml-auto h-4 w-4 shrink-0 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        </button>
                        <ul x-show="open" x-cloak class="mt-1 px-2 space-y-1 border-l border-gray-800 ml-4">
                            <li>
                                <a href="/products" class="{{ request()->routeIs('products.*') ? 'text-[#C59D5F] font-bold' : 'text-gray-500 hover:text-gray-300' }} block rounded-md py-2 pl-4 text-xs leading-6 transition-colors">
                                    Productos
                                </a>
                            </li>
                            <li>
                                <a href="/categories" class="{{ request()->routeIs('categories.*') ? 'text-[#C59D5F] font-bold' : 'text-gray-500 hover:text-gray-300' }} block rounded-md py-2 pl-4 text-xs leading-6 transition-colors">
                                    Categorías
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('purchases.index') }}" class="{{ request()->routeIs('purchases.*') ? 'text-[#C59D5F] font-bold' : 'text-gray-500 hover:text-gray-300' }} block rounded-md py-2 pl-4 text-xs leading-6 transition-colors">
                                    Compras / Ingresos
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    {{-- REPORTES --}}
                    @if(auth()->user()->role === 'admin')
                    <li>
                        <a href="{{ route('reports.index') }}"
                           class="{{ request()->routeIs('reports.*') ? 'bg-neutral-800 text-[#C59D5F] border-l-2 border-[#C59D5F]' : 'text-gray-400 hover:text-white hover:bg-neutral-800' }} group flex gap-x-3 rounded-r-md p-2 pl-3 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('reports.*') ? 'text-[#C59D5F]' : 'text-gray-500 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" /></svg>
                            Reportes Financieros
                        </a>
                    </li>
                    @endif

                    {{-- CAJA --}}
                    @if(in_array(auth()->user()->role, ['admin', 'vendedor']))
                    <li>
                        <a href="{{ route('cash.index') }}"
                           class="{{ request()->routeIs('cash.*') ? 'bg-neutral-800 text-[#C59D5F] border-l-2 border-[#C59D5F]' : 'text-gray-400 hover:text-white hover:bg-neutral-800' }} group flex gap-x-3 rounded-r-md p-2 pl-3 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('cash.*') ? 'text-[#C59D5F]' : 'text-gray-500 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Caja y Movimientos
                        </a>
                    </li>
                    @endif

                    {{-- USUARIOS --}}
                    @if(auth()->user()->role === 'admin')
                    <li>
                        <a href="/users"
                           class="{{ request()->routeIs('users.*') ? 'bg-neutral-800 text-[#C59D5F] border-l-2 border-[#C59D5F]' : 'text-gray-400 hover:text-white hover:bg-neutral-800' }} group flex gap-x-3 rounded-r-md p-2 pl-3 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('users.*') ? 'text-[#C59D5F]' : 'text-gray-500 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                            Usuarios del Sistema
                        </a>
                    </li>

                    {{-- CONFIGURACIÓN --}}
                    <li x-data="{ open: {{ request()->routeIs('branches.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" type="button"
                                class="text-gray-400 hover:text-white hover:bg-neutral-800 flex w-full items-center gap-x-3 rounded-r-md p-2 pl-3 text-sm leading-6 font-semibold text-left transition-all duration-200">
                            <svg class="h-5 w-5 shrink-0 text-gray-500 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Configuración
                            <svg :class="open ? 'rotate-90 text-[#C59D5F]' : 'text-gray-600'" class="ml-auto h-4 w-4 shrink-0 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        </button>
                        <ul x-show="open" x-cloak class="mt-1 px-2 space-y-1 border-l border-gray-800 ml-4">
                            <li>
                                <a href="{{ route('branches.index') }}"
                                   class="{{ request()->routeIs('branches.*') ? 'text-[#C59D5F] font-bold' : 'text-gray-500 hover:text-gray-300' }} block rounded-md py-2 pl-4 text-xs leading-6 transition-colors">
                                    Sucursales
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                </ul>
            </li>
        </ul>
    </nav>

    {{-- BLOQUE DE SOPORTE TÉCNICO --}}
    <div class="mt-auto pb-4 pt-6 border-t border-gray-800">
        <a href="https://wa.me/59175839845?text=Hola,%20necesito%20soporte%20con%20el%20sistema%20de%20Optica."
           target="_blank"
           class="group flex w-full items-center gap-x-3 rounded-md bg-[#C59D5F]/10 p-3 text-sm font-semibold leading-6 text-[#C59D5F] hover:bg-[#C59D5F] hover:text-white transition-all border border-[#C59D5F]/20 hover:border-[#C59D5F]">

            {{-- Ícono de WhatsApp (SVG) --}}
            <svg class="h-6 w-6 shrink-0" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>

            <div class="flex flex-col">
                <span>Soporte Técnico</span>
                <span class="text-[10px] font-normal opacity-70 group-hover:text-white">Contactar por WhatsApp</span>
            </div>
        </a>

        <div class="mt-4 text-center">
            <p class="text-[9px] text-gray-600">v1.0.0 © {{ date('Y') }}</p>
        </div>
    </div>
</div>
