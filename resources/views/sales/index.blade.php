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

    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Gestión de Ventas</h1>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm ml-4">Control de entregas, observaciones y saldos.</p>
        </div>

        {{-- BOTÓN NUEVA VENTA CON VALIDACIÓN DE CAJA --}}
        @if($hasOpenRegister)
            {{-- Si tiene caja abierta --}}
            <a href="{{ route('sales.create') }}" class="btn-gold font-bold py-2.5 px-6 rounded-sm shadow-md flex items-center gap-2 transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Nueva Venta
            </a>
        @else
            {{-- Si NO tiene caja --}}
            <button onclick="alertNoCaja()" class="bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold py-2.5 px-6 rounded-sm shadow-inner flex items-center gap-2 cursor-not-allowed border border-gray-400 dark:border-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Caja Cerrada
            </button>
        @endif
    </div>

    @if($sales->isEmpty())
        <div class="bg-white dark:bg-neutral-800 p-12 rounded-sm shadow-md text-center border-t-4 border-gray-200 dark:border-gray-700">
            <div class="inline-block p-4 rounded-full bg-gray-50 dark:bg-neutral-900 mb-3">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <p class="text-xl mb-1 text-gray-600 dark:text-gray-300 font-serif-display">No hay ventas registradas.</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm">¡Abre caja y realiza la primera venta del día!</p>
        </div>
    @else
        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F] transition-colors">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-neutral-900 text-white text-xs uppercase tracking-widest border-b border-gray-200 dark:border-neutral-700">
                            <th class="px-6 py-4 text-left font-bold">Comprobante</th>
                            <th class="px-6 py-4 text-left font-bold">Cliente / Obs.</th>
                            <th class="px-6 py-4 text-center font-bold">Entrega</th>
                            <th class="px-6 py-4 text-center font-bold">Estado</th>
                            <th class="px-6 py-4 text-center font-bold">Pago</th>
                            <th class="px-6 py-4 text-right font-bold">Total</th>
                            <th class="px-6 py-4 text-center font-bold">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-neutral-700">
                        @foreach($sales as $sale)

                        @php
                            $statusStyles = [
                                'pendiente' => 'bg-gray-100 text-gray-600 border-gray-200 dark:bg-neutral-700 dark:text-gray-400 dark:border-neutral-600',
                                'laboratorio' => 'bg-orange-50 text-orange-600 border-orange-200 dark:bg-orange-900/20 dark:text-orange-400 dark:border-orange-800',
                                'listo' => 'bg-blue-50 text-blue-600 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800',
                                'entregado' => 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800',
                                'cancelado' => 'bg-red-50 text-red-600 border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800',
                            ];
                            $currentStyle = $statusStyles[$sale->status] ?? $statusStyles['pendiente'];
                        @endphp

                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50 transition">

                            <td class="px-6 py-4 text-sm">
                                <p class="font-bold font-mono text-[#C59D5F] text-base">{{ $sale->receipt_number }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $sale->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-[10px] text-gray-400 uppercase mt-1 tracking-wide">Por: {{ strtok($sale->user->name, " ") }}</p>
                            </td>

                            <td class="px-6 py-4 text-sm max-w-xs">
                                <div class="font-bold text-gray-900 dark:text-white mb-1.5">{{ $sale->patient->name ?? 'Cliente General' }}</div>

                                @if($sale->observations)
                                    <div class="text-xs bg-[#C59D5F]/10 text-[#a37f45] dark:text-[#C59D5F] p-1.5 rounded-sm border border-[#C59D5F]/20 italic" title="{{ $sale->observations }}">
                                        Obs: {{ Str::limit($sale->observations, 30) }}
                                    </div>
                                @else
                                    <span class="text-[10px] text-gray-300 dark:text-neutral-600 italic">-- Sin observaciones --</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($sale->status == 'entregado' || $sale->status == 'cancelado')
                                    <span class="text-xs text-gray-400 font-mono">
                                        {{ $sale->delivery_date ? $sale->delivery_date->format('d/m/Y H:i') : '--' }}
                                    </span>
                                @else
                                    <form action="{{ route('sales.updateDate', $sale->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input
                                            type="datetime-local"
                                            name="delivery_date"
                                            value="{{ $sale->delivery_date ? $sale->delivery_date->format('Y-m-d\TH:i') : '' }}"
                                            class="text-xs border border-gray-200 dark:border-neutral-600 rounded-sm p-1.5 w-36 bg-white dark:bg-neutral-900 dark:text-white focus:ring-1 focus:ring-[#C59D5F] outline-none text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-neutral-800 transition"
                                            onchange="this.form.submit()"
                                            title="Cambia la fecha para guardar automáticamente"
                                        >
                                    </form>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 inline-flex text-[10px] uppercase tracking-wide font-bold rounded-full border {{ $currentStyle }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($sale->balance > 0)
                                    <div class="inline-block text-right">
                                        <p class="text-[10px] text-red-500 font-bold uppercase tracking-wider mb-0.5">Pendiente</p>
                                        <p class="font-bold text-sm text-red-600 dark:text-red-400 font-mono">Bs {{ number_format($sale->balance, 2) }}</p>
                                    </div>
                                @else
                                    <span class="px-2 py-1 inline-flex text-[10px] uppercase font-bold rounded-sm bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        Pagado
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-gray-900 dark:text-white text-base font-serif-display">
                                    Bs {{ number_format($sale->total, 2) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex item-center justify-center gap-2">

                                    {{-- IMPRIMIR --}}
                                    <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="p-2 rounded-full hover:bg-neutral-100 dark:hover:bg-neutral-700 text-gray-400 hover:text-gray-900 dark:hover:text-white transition" title="Imprimir Recibo">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>

                                    {{-- LÓGICA DE BLOQUEO --}}
                                    @if($sale->status === 'entregado' || $sale->status === 'cancelado')
                                        {{-- Iconos deshabilitados --}}
                                        <button class="p-2 rounded-full text-gray-200 dark:text-neutral-700 cursor-not-allowed">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button class="p-2 rounded-full text-gray-200 dark:text-neutral-700 cursor-not-allowed">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </button>
                                    @else
                                        {{-- ESTADO ACTIVO --}}

                                        {{-- Editar Obs --}}
                                        <button onclick="openObsModal('{{ $sale->id }}', '{{ $sale->observations }}')" class="p-2 rounded-full hover:bg-[#C59D5F]/10 text-gray-400 hover:text-[#C59D5F] transition" title="Editar Observaciones">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        {{-- Anular (Solo Admin) --}}
                                        @if(auth()->user()->role === 'admin')
                                            <button onclick="confirmAnnulment('{{ $sale->id }}', '{{ $sale->receipt_number }}')" class="p-2 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-500 transition" title="Anular y Restaurar Stock">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $sale->id }}" action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display: none;">
                                                @csrf @method('DELETE')
                                                <input type="hidden" name="reason" id="reason-{{ $sale->id }}">
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($sales, 'links'))
                <div class="px-6 py-4 bg-white dark:bg-neutral-800 border-t border-gray-100 dark:border-neutral-700">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- MODAL OBSERVACIONES --}}
    <dialog id="obsModal" class="p-0 rounded-sm shadow-2xl border-0 w-96 dark:bg-neutral-800 dark:text-white backdrop:bg-neutral-900/80 bg-transparent">
        <div class="bg-white dark:bg-neutral-800 border-t-4 border-[#C59D5F] overflow-hidden">
            <div class="p-6">
                <h3 class="font-bold text-lg mb-4 font-serif-display">Editar Observaciones</h3>
                <form id="obsForm" method="POST">
                    @csrf @method('PATCH')
                    <textarea name="observations" id="obsText" rows="4" class="w-full border p-3 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 focus:ring-1 focus:ring-[#C59D5F] outline-none text-sm" placeholder="Escribe detalles del lente..."></textarea>
                    <div class="flex justify-end gap-3 mt-4">
                        <button type="button" onclick="document.getElementById('obsModal').close()" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-800 dark:hover:text-white transition">Cancelar</button>
                        <button class="px-6 py-2 btn-gold text-white rounded-sm font-bold shadow text-sm uppercase tracking-wide">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        // Alerta si no hay caja abierta
        function alertNoCaja() {
            Swal.fire({
                title: '¡Caja Cerrada!',
                text: "Debes abrir tu caja antes de realizar ventas.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C59D5F', // Dorado
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ir a Caja',
                cancelButtonText: 'Cerrar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('cash.index') }}";
                }
            });
        }

        // Modal Observaciones
        function openObsModal(id, obs) {
            const modal = document.getElementById('obsModal');
            const form = document.getElementById('obsForm');
            const textarea = document.getElementById('obsText');
            form.action = `/ventas/${id}/observaciones`;
            textarea.value = obs || '';
            modal.showModal();
        }

        // Modal Anulación
        function confirmAnnulment(id, receipt) {
            Swal.fire({
                title: '¿Anular Venta ' + receipt + '?',
                text: "Esta acción devolverá los productos al stock. Es OBLIGATORIO indicar el motivo.",
                icon: 'warning',
                input: 'textarea',
                inputPlaceholder: 'Ej: Error de tipeo, devolución del cliente...',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c', // Rojo oscuro
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, anular venta',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('¡Necesitas escribir una justificación para auditoría!')
                    }
                    return reason
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reason-' + id).value = result.value;
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</x-app>
