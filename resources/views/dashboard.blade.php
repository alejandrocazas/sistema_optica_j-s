<x-app>
    {{-- Estilos Inline para el Dorado (Por si no están en tu config de Tailwind) --}}
    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .hover-gold:hover { background-color: #C59D5F; color: white; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>

    {{-- MENSAJE DE ÉXITO (Rediseñado en tonos dorados/suaves) --}}
    @if (session('status') === 'profile-updated')
        <div class="mb-6 bg-[#C59D5F]/10 border-l-4 border-[#C59D5F] text-gray-800 p-4 rounded-r shadow-sm animate-fade-in-down" role="alert">
            <div class="flex items-center">
                <div class="py-1"><svg class="fill-current h-6 w-6 text-[#C59D5F] mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                <div>
                    <p class="font-bold font-serif-display text-lg">Operación Exitosa</p>
                    <p class="text-sm">El sistema se ha actualizado correctamente.</p>
                </div>
            </div>
        </div>
    @endif

    {{-- ENCABEZADO DEL DASHBOARD --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-end gap-4 border-b border-gray-200 pb-6">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Panel de Control</h1>
            <p class="text-gray-500 mt-1 dark:text-gray-400">Bienvenido, <span class="font-bold text-[#C59D5F]">{{ auth()->user()->name }}</span>.</p>
        </div>

        <div class="flex flex-col items-end gap-2">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-gray-900 text-[#C59D5F] dark:bg-gray-700 shadow-md">
                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                {{ auth()->user()->branch->name ?? 'Sucursal Central' }}
            </span>
            <span class="text-sm font-serif-display italic text-gray-400">
                {{ ucfirst(now()->isoFormat('D [de] MMMM, YYYY')) }}
            </span>
        </div>
    </div>

    {{-- TARJETAS DE ESTADÍSTICAS (Diseño Luxury) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        {{-- Card 1: Ventas --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-t-4 border-[#C59D5F] hover:-translate-y-1 transition duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-[#C59D5F] opacity-5 rounded-bl-full -mr-4 -mt-4 transition group-hover:scale-110"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Ventas Hoy</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display">Bs {{ number_format($ventasHoy, 2) }}</p>
                </div>
                <div class="p-3 bg-[#C59D5F]/10 rounded-full text-[#C59D5F]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Card 2: Laboratorio --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-t-4 border-gray-800 dark:border-gray-600 hover:-translate-y-1 transition duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gray-400 opacity-5 rounded-bl-full -mr-4 -mt-4 transition group-hover:scale-110"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">En Laboratorio</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display">{{ $trabajosPendientes }}</p>
                </div>
                <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-full text-gray-600 dark:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Card 3: Pacientes --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-t-4 border-[#C59D5F] hover:-translate-y-1 transition duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-[#C59D5F] opacity-5 rounded-bl-full -mr-4 -mt-4 transition group-hover:scale-110"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pacientes</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display">{{ $pacientesTotal }}</p>
                </div>
                <div class="p-3 bg-[#C59D5F]/10 rounded-full text-[#C59D5F]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Card 4: Alertas (Stock) --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-t-4 border-red-500 hover:-translate-y-1 transition duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-red-500 opacity-5 rounded-bl-full -mr-4 -mt-4 transition group-hover:scale-110"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Stock Bajo</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400 font-serif-display">{{ $productosBajoStock }}</p>
                </div>
                <div class="p-3 bg-red-50 dark:bg-red-900/30 rounded-full text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- SECCIÓN DE ACCESOS RÁPIDOS (Minimalista) --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700 h-fit">
            <h3 class="font-bold font-serif-display text-xl text-gray-800 dark:text-white mb-6 flex items-center">
                <span class="w-1 h-6 bg-[#C59D5F] mr-3 rounded-full"></span>
                Acciones Rápidas
            </h3>
            <div class="grid grid-cols-2 gap-4">

                <a href="{{ route('sales.create') }}" class="group flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded transition duration-300 border border-transparent hover:border-[#C59D5F] hover:bg-white hover:shadow-md cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-[#C59D5F]/10 flex items-center justify-center text-[#C59D5F] group-hover:bg-[#C59D5F] group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="mt-3 font-semibold text-sm text-gray-600 group-hover:text-[#C59D5F]">Nueva Venta</span>
                </a>

                <a href="{{ route('patients.create') }}" class="group flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded transition duration-300 border border-transparent hover:border-[#C59D5F] hover:bg-white hover:shadow-md cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-[#C59D5F]/10 flex items-center justify-center text-[#C59D5F] group-hover:bg-[#C59D5F] group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <span class="mt-3 font-semibold text-sm text-gray-600 group-hover:text-[#C59D5F]">Nuevo Paciente</span>
                </a>

                <a href="{{ route('cash.index') }}" class="group flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded transition duration-300 border border-transparent hover:border-[#C59D5F] hover:bg-white hover:shadow-md cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-[#C59D5F]/10 flex items-center justify-center text-[#C59D5F] group-hover:bg-[#C59D5F] group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="mt-3 font-semibold text-sm text-gray-600 group-hover:text-[#C59D5F]">Abrir Caja</span>
                </a>

                <a href="{{ route('products.create') }}" class="group flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded transition duration-300 border border-transparent hover:border-[#C59D5F] hover:bg-white hover:shadow-md cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-[#C59D5F]/10 flex items-center justify-center text-[#C59D5F] group-hover:bg-[#C59D5F] group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <span class="mt-3 font-semibold text-sm text-gray-600 group-hover:text-[#C59D5F]">Inventario</span>
                </a>

            </div>
        </div>

        {{-- TABLA DE VENTAS --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold font-serif-display text-xl text-gray-800 dark:text-white flex items-center">
                    <span class="w-1 h-6 bg-gray-800 dark:bg-gray-500 mr-3 rounded-full"></span>
                    Últimas Ventas
                </h3>
                <a href="{{ route('sales.index') }}" class="text-sm font-bold text-[#C59D5F] hover:text-gray-900 transition flex items-center">
                    Ver todas
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-900 text-gray-100 uppercase font-medium text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">Cliente</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-center rounded-tr-lg">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($ultimasVentas as $v)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                            <td class="px-4 py-4 font-medium">{{ $v->patient->name ?? 'Cliente General' }}</td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $estadoClases = match($v->status) {
                                        'entregado' => 'bg-gray-800 text-white',
                                        'cancelado' => 'bg-red-100 text-red-700',
                                        'listo' => 'bg-[#C59D5F] text-white',
                                        default => 'bg-gray-100 text-gray-600 border border-gray-200'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $estadoClases }}">
                                    {{ ucfirst($v->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right font-serif-display font-bold text-gray-900 dark:text-white">Bs {{ number_format($v->total, 2) }}</td>
                            <td class="px-4 py-4 text-center">
                                <a href="{{ route('sales.print', $v->id) }}" target="_blank" class="text-gray-400 hover:text-[#C59D5F] transition" title="Imprimir Recibo">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app>
