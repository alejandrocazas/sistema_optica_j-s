<x-app>
    {{-- Estilos específicos --}}
    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
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

    {{-- ENCABEZADO --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Historial de Compras</h1>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm ml-4">Registro de ingresos de mercancía al inventario.</p>
        </div>

        <a href="{{ route('purchases.create') }}" class="btn-gold font-bold py-2.5 px-6 rounded-sm shadow-md flex items-center gap-2 transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wider">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Registrar Compra
        </a>
    </div>

    {{-- TABLA DE COMPRAS --}}
    <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F] transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-neutral-900 text-white text-xs uppercase tracking-widest border-b border-gray-200 dark:border-neutral-700">
                        <th class="px-6 py-4 text-left font-bold">Fecha y Hora</th>
                        <th class="px-6 py-4 text-left font-bold">Registrado Por</th>
                        <th class="px-6 py-4 text-center font-bold">Sucursal Destino</th>
                        <th class="px-6 py-4 text-right font-bold">Total Compra</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-neutral-700">
                    @forelse($purchases as $purchase)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50 transition bg-white dark:bg-neutral-800 group">

                        {{-- FECHA --}}
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-[#C59D5F]/10 text-[#C59D5F] rounded-full group-hover:bg-[#C59D5F] group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-gray-900 dark:text-white font-bold whitespace-no-wrap text-base">
                                        {{ $purchase->created_at->format('d/m/Y') }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 font-mono">
                                        {{ $purchase->created_at->format('H:i A') }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- USUARIO --}}
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-gray-100 dark:bg-neutral-700 rounded-full flex items-center justify-center text-xs font-bold uppercase text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-neutral-600">
                                    {{ substr($purchase->user->name ?? '?', 0, 2) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-700 dark:text-gray-300 font-medium whitespace-no-wrap">
                                        {{ $purchase->user->name ?? 'Sistema' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- SUCURSAL --}}
                        <td class="px-6 py-4 text-sm text-center">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold uppercase tracking-wide rounded-full bg-neutral-100 dark:bg-neutral-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-neutral-700">
                                {{ $purchase->branch->name ?? 'General' }}
                            </span>
                        </td>

                        {{-- TOTAL --}}
                        <td class="px-6 py-4 text-sm text-right">
                            <span class="text-xl font-bold text-gray-900 dark:text-white font-serif-display">
                                Bs {{ number_format($purchase->total_cost, 2) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <div class="p-4 bg-gray-50 dark:bg-neutral-900 rounded-full mb-3">
                                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <p class="font-medium text-sm">No se han registrado compras aún.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        @if(method_exists($purchases, 'links'))
            <div class="px-6 py-4 bg-white dark:bg-neutral-800 border-t border-gray-100 dark:border-neutral-700">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</x-app>
