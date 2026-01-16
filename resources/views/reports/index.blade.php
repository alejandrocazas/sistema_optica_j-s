<x-app>
    {{-- Estilos específicos --}}
    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .focus-gold:focus { --tw-ring-color: #C59D5F; border-color: #C59D5F; }
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
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Reportes Financieros</h1>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 ml-4">
            Resumen económico y flujo de caja del: <strong class="text-gray-800 dark:text-white">{{ $startDate->format('d/m/Y') }}</strong> al <strong class="text-gray-800 dark:text-white">{{ $endDate->format('d/m/Y') }}</strong>
        </p>
    </div>

    {{-- BARRA DE FILTROS --}}
    <div class="bg-white dark:bg-neutral-800 p-6 rounded-sm shadow-xl border-t-4 border-[#C59D5F] mb-8 transition-colors">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col lg:flex-row items-end justify-between gap-6">

            <div class="flex flex-wrap items-end gap-4 w-full lg:w-auto">
                {{-- SELECTOR DE SUCURSAL (SOLO ADMIN) --}}
                @if(auth()->user()->role === 'admin')
                    <div class="w-full sm:w-auto">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Sucursal</label>
                        <div class="relative">
                            <select name="branch_id" onchange="this.form.submit()"
                                class="w-full sm:w-48 p-2.5 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none text-sm font-bold appearance-none cursor-pointer">
                                <option value="">-- TODAS --</option>
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- PERIODOS PREDEFINIDOS --}}
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Periodo</label>
                    <div class="relative">
                        <select name="filter" onchange="if(this.value!='custom') this.form.submit()"
                            class="w-full sm:w-40 p-2.5 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none text-sm appearance-none cursor-pointer">
                            <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Hoy</option>
                            <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Esta Semana</option>
                            <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Este Mes</option>
                            <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>Este Año</option>
                            <option value="custom" {{ $filter == 'custom' ? 'selected' : '' }}>Personalizado</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                {{-- FECHAS PERSONALIZADAS --}}
                @if($filter == 'custom')
                <div class="flex gap-2 w-full sm:w-auto">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Desde</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="p-2.5 border border-gray-200 rounded-sm bg-white dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Hasta</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="p-2.5 border border-gray-200 rounded-sm bg-white dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none text-sm">
                    </div>
                </div>
                @endif

                <button type="submit" class="w-full sm:w-auto btn-gold font-bold py-2.5 px-6 rounded-sm shadow-md transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wider">
                    Filtrar
                </button>
            </div>

            <div class="w-full lg:w-auto">
                <a href="{{ route('reports.pdf', request()->all()) }}" target="_blank"
                   class="w-full sm:w-auto bg-neutral-900 hover:bg-black text-white py-2.5 px-6 rounded-sm font-bold flex items-center justify-center gap-2 shadow transition border border-gray-800 text-sm uppercase tracking-wider">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#C59D5F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exportar PDF
                </a>
            </div>
        </form>
    </div>

    {{-- GRID DE INDICADORES (KPIs) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

        {{-- Card 1: Ventas --}}
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-sm shadow-md border-l-4 border-blue-500 relative overflow-hidden group">
            <div class="relative z-10">
                <h3 class="font-bold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-widest mb-1">Ventas Totales</h3>
                <p class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display">Bs {{ number_format($totalSales, 2) }}</p>
                <span class="text-[10px] text-blue-500 font-bold uppercase tracking-wide mt-2 block">Facturación Bruta</span>
            </div>
            <div class="absolute right-4 top-4 text-blue-500/10 group-hover:text-blue-500/20 transition-colors">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M16 6v2h2l2 12H0L2 8h2V6a3 3 0 016 0h4a3 3 0 012 0zm-2 0H6a1 1 0 000 2h8a1 1 0 000-2zM10 10H8v2h2v-2zm4 0h-2v2h2v-2z"></path></svg>
            </div>
        </div>

        {{-- Card 2: Ingresos Reales --}}
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-sm shadow-md border-l-4 border-emerald-500 relative overflow-hidden group">
            <div class="relative z-10">
                <h3 class="font-bold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-widest mb-1">Ingresos Reales</h3>
                <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 font-serif-display">Bs {{ number_format($totalIncome, 2) }}</p>
                <span class="text-[10px] text-emerald-600 dark:text-emerald-500 font-bold uppercase tracking-wide mt-2 block">Efectivo Recibido</span>
            </div>
            <div class="absolute right-4 top-4 text-emerald-500/10 group-hover:text-emerald-500/20 transition-colors">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05 1.18 1.91 2.53 1.91 1.38 0 2.29-.84 2.29-2.12 0-1.39-1.11-1.82-2.95-2.42-2.18-.7-4.1-1.56-4.1-3.98 0-2.01 1.59-3.17 3.54-3.6V2h2.67v1.9c1.7.35 3.02 1.43 3.15 3.36h-1.98c-.16-.9-1.04-1.67-2.31-1.67-1.31 0-2.14.83-2.14 1.94 0 1.28 1.04 1.84 2.92 2.45 2.13.71 4.18 1.58 4.18 3.97 0 2.1-1.72 3.37-3.92 3.81z"></path></svg>
            </div>
        </div>

        {{-- Card 3: Gastos --}}
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-sm shadow-md border-l-4 border-red-500 relative overflow-hidden group">
            <div class="relative z-10">
                <h3 class="font-bold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-widest mb-1">Gastos Operativos</h3>
                <p class="text-3xl font-bold text-red-600 dark:text-red-400 font-serif-display">Bs {{ number_format($totalExpenses, 2) }}</p>
                <span class="text-[10px] text-red-500 font-bold uppercase tracking-wide mt-2 block">Salidas de Caja</span>
            </div>
            <div class="absolute right-4 top-4 text-red-500/10 group-hover:text-red-500/20 transition-colors">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
        </div>

        {{-- Card 4: Neto --}}
        <div class="bg-neutral-900 p-6 rounded-sm shadow-lg border border-[#C59D5F] relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="font-bold text-[#C59D5F] text-xs uppercase tracking-widest mb-1">Utilidad Neta</h3>
                <p class="text-3xl font-bold font-serif-display {{ $netProfit >= 0 ? 'text-white' : 'text-red-400' }}">
                    Bs {{ number_format($netProfit, 2) }}
                </p>
                <span class="text-[10px] text-gray-400 uppercase tracking-wide mt-2 block">Ingresos - Gastos</span>
            </div>
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#C59D5F] rounded-full opacity-10 blur-xl"></div>
        </div>
    </div>

    {{-- TABLA DETALLE --}}
    <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F]">
        <div class="px-6 py-4 bg-white dark:bg-neutral-800 border-b border-gray-100 dark:border-neutral-700 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 dark:text-white font-serif-display">Detalle de Ventas del Periodo</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-neutral-900 text-white text-xs uppercase tracking-widest border-b border-gray-200 dark:border-neutral-700">
                        <th class="px-6 py-4 text-left font-bold">Fecha</th>
                        <th class="px-6 py-4 text-left font-bold">Comprobante</th>
                        <th class="px-6 py-4 text-left font-bold">Cliente</th>
                        <th class="px-6 py-4 text-right font-bold">Total Venta</th>
                        <th class="px-6 py-4 text-right font-bold">Monto Pagado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-neutral-700">
                    @foreach($sales as $sale)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50 transition bg-white dark:bg-neutral-800">
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $sale->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 font-mono text-sm text-[#C59D5F] font-bold">
                            {{ $sale->receipt_number }}
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">
                            {{ $sale->patient->name ?? 'Cliente General' }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800 dark:text-white">
                            Bs {{ number_format($sale->total, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($sale->balance > 0)
                                <span class="text-gray-500 dark:text-gray-400 text-xs font-bold mr-2">(Saldo: {{ number_format($sale->balance, 2) }})</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">Bs {{ number_format($sale->paid_amount, 2) }}</span>
                            @else
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">Bs {{ number_format($sale->paid_amount, 2) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    @if($sales->isEmpty())
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <div class="p-4 bg-gray-50 dark:bg-neutral-900 rounded-full mb-3">
                                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="font-medium">No hay movimientos registrados en este periodo.</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app>
