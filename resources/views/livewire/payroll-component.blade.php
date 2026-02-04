<div>
    {{-- EL DIV PADRE OBLIGATORIO --}}

    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .focus-gold:focus { --tw-ring-color: #C59D5F; border-color: #C59D5F; }
        .font-serif-display { font-family: 'Playfair Display', serif; }

        .btn-gold {
            background: linear-gradient(135deg, #C59D5F 0%, #a37f45 100%);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d6ad6d 0%, #b89050 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(197, 157, 95, 0.4);
        }
    </style>

    <div class="p-6">
        {{-- ENCABEZADO --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-end gap-4 border-b border-gray-200 pb-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">
                    Planilla de Sueldos
                </h1>
                <p class="text-gray-500 mt-1 dark:text-gray-400">
                    Gestión de pagos y asistencia del personal.
                </p>
            </div>

            <div class="flex flex-col items-end gap-2">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-gray-900 text-[#C59D5F] dark:bg-gray-700 shadow-md">
                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    {{ auth()->user()->branch->name ?? 'Administración Central' }}
                </span>
                <span class="text-sm font-serif-display italic text-gray-400">
                    {{ ucfirst(now()->isoFormat('D [de] MMMM, YYYY')) }}
                </span>
            </div>
        </div>

        {{-- BARRA DE FILTROS --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8 border-l-4 border-[#C59D5F]">
            <div class="flex flex-col lg:flex-row gap-6 justify-between items-end">
                <div class="flex flex-wrap gap-4 w-full lg:w-auto">
                    <div class="w-full sm:w-auto">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Sucursal</label>
                        <select wire:model.live="branch_id" class="w-full sm:w-48 p-2.5 border border-gray-200 rounded bg-gray-50 text-sm focus:ring-1 focus-gold outline-none">
                            <option value="">-- TODAS --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-auto">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Mes</label>
                        <select wire:model.live="selectedMonth" class="w-full sm:w-32 p-2.5 border border-gray-200 rounded bg-gray-50 text-sm font-bold focus:ring-1 focus-gold outline-none">
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-auto">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Año</label>
                        <input type="number" wire:model.live="selectedYear" class="w-full sm:w-24 p-2.5 border border-gray-200 rounded bg-gray-50 text-sm focus:ring-1 focus-gold outline-none">
                    </div>
                </div>
                <div class="text-right text-sm text-gray-500 bg-gray-50 p-3 rounded border border-gray-100">
                    <p>ℹ️ <strong>Multa Atraso:</strong> <span class="text-[#C59D5F] font-bold">Bs {{ $penaltyPerLate }}</span></p>
                    <p>ℹ️ <strong>Falta:</strong> <span class="text-red-500 font-bold">1 Día Haber (Sueldo/30)</span></p>
                </div>
            </div>
        </div>

        {{-- TARJETAS DE RESUMEN --}}
        @php
            $totalBase = 0;
            $totalDescuentos = 0;
            $totalPagable = 0;
            foreach($payrollData as $data) {
                $totalBase += $data['base_salary'];
                $desc = ($data['lates'] * $penaltyPerLate) + ($data['absences'] * ($data['base_salary']/30));
                $totalDescuentos += $desc;
                $totalPagable += ($data['base_salary'] - $desc + $data['bonuses']);
            }
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-5 rounded-lg shadow border-t-4 border-gray-800">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Planilla (Base)</p>
                <p class="text-2xl font-bold text-gray-800 font-serif-display">Bs {{ number_format($totalBase, 2) }}</p>
            </div>
            <div class="bg-white p-5 rounded-lg shadow border-t-4 border-red-500">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Descuentos</p>
                <p class="text-2xl font-bold text-red-600 font-serif-display">- Bs {{ number_format($totalDescuentos, 2) }}</p>
            </div>
            <div class="bg-white p-5 rounded-lg shadow border-t-4 border-[#C59D5F]">
                <p class="text-xs font-bold text-[#C59D5F] uppercase tracking-widest">Total a Pagar</p>
                <p class="text-2xl font-bold text-gray-900 font-serif-display">Bs {{ number_format($totalPagable, 2) }}</p>
            </div>
        </div>

        {{-- TABLA DE EMPLEADOS --}}
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-900 text-gray-100 uppercase font-medium text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Empleado</th>
                            <th class="px-6 py-4 text-right">Sueldo Base</th>
                            <th class="px-6 py-4 text-center bg-[#C59D5F]/20 text-[#C59D5F]">Atrasos</th>
                            <th class="px-6 py-4 text-center bg-red-900/20 text-red-400">Faltas (Días)</th>
                            <th class="px-6 py-4 text-center bg-green-900/20 text-green-400">Bonos</th>
                            <th class="px-6 py-4 text-right">Líquido Pagable</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                        @forelse($payrollData as $id => $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white text-base">{{ $data['name'] }}</div>
                                <div class="text-xs text-[#C59D5F] uppercase tracking-wide">{{ $data['position'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold">
                                {{ number_format($data['base_salary'], 2) }}
                            </td>
                            <td class="px-6 py-4 text-center bg-[#C59D5F]/5">
                                <input type="number" min="0" wire:model.live="payrollData.{{ $id }}.lates"
                                       class="w-20 text-center border-gray-300 rounded focus:ring-[#C59D5F] focus:border-[#C59D5F] text-gray-900 font-bold">
                                <div class="text-[10px] text-red-500 mt-1 font-bold">
                                    -{{ number_format($data['lates'] * $penaltyPerLate, 0) }} Bs
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center bg-red-50">
                                <input type="number" min="0" wire:model.live="payrollData.{{ $id }}.absences"
                                       class="w-20 text-center border-gray-300 rounded focus:ring-red-500 focus:border-red-500 text-gray-900 font-bold">
                                <div class="text-[10px] text-red-500 mt-1 font-bold">
                                    @php $dayValue = $data['base_salary']/30; @endphp
                                    -{{ number_format($data['absences'] * $dayValue, 2) }} Bs
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <input type="number" min="0" wire:model.live="payrollData.{{ $id }}.bonuses"
                                       class="w-24 text-center border-gray-300 rounded focus:ring-green-500 focus:border-green-500 text-gray-900 font-bold">
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php
                                    $discountL = $data['lates'] * $penaltyPerLate;
                                    $discountA = $data['absences'] * ($data['base_salary']/30);
                                    $total = $data['base_salary'] - $discountL - $discountA + $data['bonuses'];
                                @endphp
                                <span class="text-xl font-serif-display font-bold {{ $total < 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    Bs {{ number_format(max(0, $total), 2) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <p>No hay empleados registrados en esta sucursal.</p>
                                <a href="{{ route('employees.index') }}" class="text-[#C59D5F] font-bold mt-2 hover:underline">Registrar Empleados</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        
        {{-- BOTÓN GUARDAR Y ACCIONES --}}
        <div class="mt-8 flex justify-end gap-4">

            {{-- LÓGICA PHP (Faltaba esto en tu código) --}}
            @php
                // 1. Verificamos si ya existe planilla guardada para este periodo
                $existePlanilla = \App\Models\PayrollDetail::where('month', $selectedMonth)
                                    ->where('year', $selectedYear)
                                    ->when($branch_id, function($q) {
                                        $q->whereHas('employee', fn($q2) => $q2->where('branch_id', $this->branch_id));
                                    })->exists();

                // 2. Calculamos el nombre del mes en español (sin errores de Carbon)
                $mesNombre = strtoupper(\Carbon\Carbon::create(null, (int)$selectedMonth, 1)->locale('es')->monthName);
            @endphp

            {{-- BOTÓN GRIS: IMPRIMIR COPIA (Solo aparece si ya existe la planilla) --}}
            @if($existePlanilla)
                <a href="{{ route('payroll.print', ['month' => $selectedMonth, 'year' => $selectedYear, 'branch_id' => $branch_id]) }}"
                   target="_blank"
                   class="bg-gray-800 text-white font-bold py-4 px-6 rounded shadow-lg text-sm uppercase tracking-wider flex items-center gap-2 hover:bg-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    IMPRIMIR COPIA DE {{ $mesNombre }}
                </a>
            @endif

            {{-- BOTÓN DORADO: GUARDAR/ACTUALIZAR (Siempre visible) --}}
            <button wire:click="savePayroll" class="btn-gold font-bold py-4 px-10 rounded shadow-lg text-sm uppercase tracking-wider flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                {{ $existePlanilla ? 'ACTUALIZAR Y REIMPRIMIR' : 'GUARDAR Y GENERAR' }} PLANILLA DE {{ $mesNombre }}
            </button>
        </div>
    </div>
</div>
