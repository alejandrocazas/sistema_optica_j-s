<x-app>
    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        {{-- ENCABEZADO --}}
        <div class="flex justify-between items-end mb-8 border-b border-gray-200 dark:border-neutral-700 pb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide flex items-center gap-3">
                    <svg class="w-8 h-8 text-[#C59D5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    Control de Suscripciones (SaaS)
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    Gestiona los pagos de instalación y mensualidades de tus clientes.
                </p>
            </div>
        </div>

        {{-- TABLA DE CLIENTES --}}
        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden border-t-4 border-[#C59D5F]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-neutral-900 text-[#C59D5F] text-xs uppercase tracking-wider">
                    <tr>
                        <th class="p-4 font-bold">Sucursal / Cliente</th>
                        <th class="p-4 font-bold text-center">Pago de Instalación</th>
                        <th class="p-4 font-bold text-center">Estado Mensualidad</th>
                        <th class="p-4 font-bold text-right">Acción Rápida</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100 dark:divide-neutral-700 text-gray-300">
                    @foreach($branches as $branch)
                        @php
                            $diasRestantes = $branch->next_payment_date ? now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($branch->next_payment_date)->startOfDay(), false) : null;
                        @endphp
                        <tr class="hover:bg-neutral-700/30 transition">
                            {{-- NOMBRE --}}
                            <td class="p-4 font-bold text-gray-900 dark:text-white text-base">
                                {{ $branch->name }}
                            </td>

                            {{-- INSTALACIÓN --}}
                            <td class="p-4 text-center">
                                @if($branch->installation_paid)
                                    <span class="px-3 py-1 bg-green-900/30 text-green-400 border border-green-800 rounded-full text-xs font-bold tracking-wide">PAGADO</span>
                                @else
                                    <span class="px-3 py-1 bg-red-900/30 text-red-400 border border-red-800 rounded-full text-xs font-bold tracking-wide">DEBE BS 800</span>
                                @endif
                            </td>

                            {{-- MENSUALIDAD --}}
                            <td class="p-4 text-center">
                                @if(!$branch->next_payment_date)
                                    <span class="text-gray-500 italic text-xs">Aún no inicia</span>
                                @elseif($diasRestantes < 0)
                                    <span class="text-red-500 font-bold">Vencido hace {{ abs($diasRestantes) }} días</span>
                                    <br><span class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($branch->next_payment_date)->format('d/m/Y') }}</span>
                                @elseif($diasRestantes <= 5)
                                    <span class="text-yellow-500 font-bold">Vence en {{ $diasRestantes }} días</span>
                                    <br><span class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($branch->next_payment_date)->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-green-400 font-bold">Activo (Faltan {{ $diasRestantes }} días)</span>
                                    <br><span class="text-[10px] text-gray-500">Vence: {{ \Carbon\Carbon::parse($branch->next_payment_date)->format('d/m/Y') }}</span>
                                @endif
                            </td>

                            {{-- BOTONES DE ACCIÓN --}}
                            <td class="p-4 text-right flex flex-col gap-2 items-end">

                                {{-- Botón: Pagar Instalación --}}
                                @if(!$branch->installation_paid)
                                    <form action="{{ route('billing.pay_installation', $branch->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" onclick="return confirm('¿Confirmar pago de Bs 800 por instalación?')" class="bg-red-900 hover:bg-red-800 text-white border border-red-700 px-3 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition shadow-lg">
                                            Cobrar Instalación
                                        </button>
                                    </form>
                                @endif

                                {{-- Botón: Renovar Mes --}}
                                <form action="{{ route('billing.renew_subscription', $branch->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" onclick="return confirm('¿Confirmar renovación por 1 mes?')" class="bg-[#C59D5F] hover:bg-[#b38b4f] text-white px-3 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition shadow-lg">
                                        Renovar 1 Mes
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>
