<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 font-serif-display">Gestión de Planilla Salarial</h1>

        <div class="flex gap-2">
            {{-- Filtros --}}
            <select wire:model.live="branch_id" class="p-2 border rounded">
                <option value="">Todas las Sucursales</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="selectedMonth" class="p-2 border rounded font-bold">
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                {{-- ... resto de meses ... --}}
            </select>

            <input type="number" wire:model.live="selectedYear" class="p-2 border rounded w-20">
        </div>
    </div>

    {{-- CONFIGURACIÓN RÁPIDA (Solo visual) --}}
    <div class="bg-blue-50 p-3 rounded mb-4 text-sm text-blue-800 flex gap-6">
        <span>ℹ️ <strong>Cálculo:</strong> 1 Falta = 1 Día de Haber (Sueldo/30)</span>
        <span>ℹ️ <strong>Multa Atraso:</strong> Bs {{ $penaltyPerLate }} (Fijo)</span>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-neutral-900 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">Empleado</th>
                    <th class="px-4 py-3 text-right">Sueldo Base</th>
                    <th class="px-4 py-3 text-center bg-yellow-600">Atrasos (Cant)</th>
                    <th class="px-4 py-3 text-center bg-red-600">Faltas (Días)</th>
                    <th class="px-4 py-3 text-center bg-green-600">Bonos (Bs)</th>
                    <th class="px-4 py-3 text-right font-bold text-lg">Líquido Pagable</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($payrollData as $id => $data)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-bold text-gray-900">{{ $data['name'] }}</div>
                        <div class="text-xs text-gray-500">{{ $data['position'] }}</div>
                    </td>

                    <td class="px-4 py-3 text-right font-mono">
                        {{ number_format($data['base_salary'], 2) }}
                    </td>

                    {{-- INPUTS EDITABLES --}}
                    <td class="px-4 py-3 text-center">
                        <input type="number" min="0" wire:model.live="payrollData.{{ $id }}.lates"
                               class="w-16 p-1 text-center border border-gray-300 rounded focus:ring-yellow-500 focus:border-yellow-500">
                        <div class="text-[10px] text-red-500 mt-1">
                            -{{ number_format($data['lates'] * $penaltyPerLate, 0) }} Bs
                        </div>
                    </td>

                    <td class="px-4 py-3 text-center">
                        <input type="number" min="0" wire:model.live="payrollData.{{ $id }}.absences"
                               class="w-16 p-1 text-center border border-gray-300 rounded focus:ring-red-500 focus:border-red-500">
                        <div class="text-[10px] text-red-500 mt-1">
                            @php $dayValue = $data['base_salary']/30; @endphp
                            -{{ number_format($data['absences'] * $dayValue, 2) }} Bs
                        </div>
                    </td>

                    <td class="px-4 py-3 text-center">
                        <input type="number" min="0" wire:model.live="payrollData.{{ $id }}.bonuses"
                               class="w-20 p-1 text-center border border-gray-300 rounded focus:ring-green-500 focus:border-green-500">
                    </td>

                    {{-- CÁLCULO EN TIEMPO REAL (VISUAL) --}}
                    <td class="px-4 py-3 text-right">
                        @php
                            $discountL = $data['lates'] * $penaltyPerLate;
                            $discountA = $data['absences'] * ($data['base_salary']/30);
                            $total = $data['base_salary'] - $discountL - $discountA + $data['bonuses'];
                        @endphp
                        <span class="font-bold text-lg {{ $total < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                            Bs {{ number_format(max(0, $total), 2) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        <button wire:click="savePayroll" class="btn-gold font-bold py-3 px-8 rounded shadow-lg text-sm uppercase tracking-wider flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            GUARDAR PLANILLA DEL MES
        </button>
    </div>
</div>
