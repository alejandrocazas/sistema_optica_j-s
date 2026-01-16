<x-app>
    {{-- Estilos Específicos --}}
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
    <div class="flex justify-between items-end mb-8">
        <div class="flex items-center gap-3">
            <div class="h-8 w-1 bg-[#C59D5F] rounded-full"></div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Control de Caja</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gestión de ingresos, egresos y cierre de turno.</p>
            </div>
        </div>
        <div class="text-sm font-bold text-gray-400 bg-gray-100 dark:bg-neutral-800 px-4 py-2 rounded-full border border-gray-200 dark:border-neutral-700">
            {{ now()->isoFormat('D [de] MMMM, YYYY') }}
        </div>
    </div>

    @if(!$currentRegister)
        {{-- VISTA: CAJA CERRADA --}}
        <div class="bg-white dark:bg-neutral-800 border-l-4 border-red-500 p-8 rounded-sm shadow-xl mb-8 relative overflow-hidden">
            {{-- Decoración de fondo --}}
            <div class="absolute right-0 top-0 h-full w-32 bg-gradient-to-l from-red-50 to-transparent dark:from-red-900/20 pointer-events-none"></div>

            <div class="relative z-10">
                <h2 class="text-2xl font-bold text-red-600 dark:text-red-400 mb-2 font-serif-display flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Caja Cerrada
                </h2>
                <p class="mb-6 text-gray-600 dark:text-gray-300">El sistema de cobros está deshabilitado. Ingrese el monto inicial para comenzar.</p>

                <form id="form-open-box" action="{{ route('cash.open') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                    @csrf
                    <div class="w-full sm:w-64">
                        <label class="block font-bold text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Monto Inicial (Bs)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400 font-bold">Bs</span>
                            <input type="number" name="amount" class="pl-10 p-3 w-full text-lg font-bold text-gray-900 dark:text-white bg-gray-50 dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-sm focus:ring-1 focus-gold outline-none transition" placeholder="0.00" required>
                        </div>
                    </div>
                    <button onclick="confirmAction(event, 'form-open-box', '¿Abrir Caja?', 'Se registrará el inicio de operaciones del día.', 'Sí, abrir caja')"
                            class="w-full sm:w-auto btn-gold font-bold py-3 px-8 rounded-sm shadow-md transition transform hover:-translate-y-0.5 text-sm uppercase tracking-widest">
                        ABRIR CAJA
                    </button>
                </form>
            </div>
        </div>
    @else
        {{-- VISTA: CAJA ABIERTA (DASHBOARD) --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

            {{-- Card 1: Inicial --}}
            <div class="bg-white dark:bg-neutral-800 p-6 rounded-sm shadow-md border-t-4 border-[#C59D5F]">
                <h3 class="font-bold text-gray-400 text-xs uppercase tracking-widest">Fondo Inicial</h3>
                <p class="text-2xl font-bold text-[#C59D5F] mt-2 font-serif-display">Bs {{ number_format($currentRegister->opening_amount, 2) }}</p>
                <div class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $currentRegister->opened_at ? $currentRegister->opened_at->format('H:i A') : '--:--' }}
                </div>
            </div>

            {{-- Card 2: Ingresos --}}
            <div class="bg-white dark:bg-neutral-800 p-6 rounded-sm shadow-md border-t-4 border-emerald-500">
                <h3 class="font-bold text-gray-400 text-xs uppercase tracking-widest">Ventas del Turno</h3>
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-2 font-serif-display">+ Bs {{ number_format($totalIngresos ?? 0, 2) }}</p>
                <div class="text-xs text-gray-400 mt-1">Efectivo, QR y Tarjeta</div>
            </div>

            {{-- Card 3: Egresos --}}
            <div class="bg-white dark:bg-neutral-800 p-6 rounded-sm shadow-md border-t-4 border-red-500 relative group">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-gray-400 text-xs uppercase tracking-widest">Gastos / Salidas</h3>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2 font-serif-display">- Bs {{ number_format($totalEgresos ?? 0, 2) }}</p>
                    </div>
                    <button onclick="document.getElementById('modal-gasto').showModal()" class="text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 p-2 rounded-full transition" title="Registrar Gasto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>
                </div>
            </div>

            {{-- Card 4: Total --}}
            <div class="bg-neutral-900 p-6 rounded-sm shadow-lg border border-[#C59D5F] flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#C59D5F] rounded-full opacity-10 blur-xl"></div>
                <div class="relative z-10">
                    <h3 class="font-bold text-[#C59D5F] text-xs uppercase tracking-widest">Total en Caja</h3>
                    <p class="text-3xl font-bold text-white mt-1 font-serif-display">Bs {{ number_format(($currentRegister->opening_amount + ($totalIngresos ?? 0)) - ($totalEgresos ?? 0), 2) }}</p>
                </div>

                <form id="form-close-box" action="{{ route('cash.close') }}" method="POST" target="_blank" class="relative z-10">
                    @csrf
                    <button onclick="closeRegister(event)" class="mt-4 w-full bg-red-700 hover:bg-red-600 text-white py-2 rounded-sm font-bold text-xs uppercase tracking-wider transition border border-red-800">
                        Cerrar Caja y Reportar
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- TABLA DE PENDIENTES --}}
    <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border-t-4 border-[#C59D5F]">
        <div class="p-6 bg-white dark:bg-neutral-800 border-b border-gray-100 dark:border-neutral-700 flex justify-between items-center">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white font-serif-display">Trabajos Pendientes de Entrega / Cobro</h3>
            <span class="text-xs font-bold bg-[#C59D5F]/10 text-[#C59D5F] px-3 py-1 rounded-full border border-[#C59D5F]/20">{{ $pendingWorks->total() }} pendientes</span>
        </div>

        @if($pendingWorks->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <div class="inline-block p-4 rounded-full bg-gray-50 dark:bg-neutral-900 mb-3">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p>¡Todo al día! No hay saldos pendientes.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="text-xs uppercase bg-neutral-900 text-white border-b border-gray-200">
                        <th class="px-6 py-4 text-left font-bold tracking-wider">Comprobante</th>
                        <th class="px-6 py-4 text-left font-bold tracking-wider">Cliente</th>
                        <th class="px-6 py-4 text-center font-bold tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-right font-bold tracking-wider">Saldo</th>
                        <th class="px-6 py-4 text-center font-bold tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-neutral-700">
                    @foreach($pendingWorks as $work)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-[#C59D5F]">{{ $work->receipt_number }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-white">
                                {{ $work->patient->name ?? 'Cliente General / Eliminado' }}
                            </div>
                            <div class="text-xs text-gray-400">{{ $work->created_at->format('d/m/Y') }}</div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($work->status == 'laboratorio')
                                <span class="bg-gray-100 text-gray-600 dark:bg-neutral-700 dark:text-gray-300 text-[10px] px-2 py-1 rounded-full font-bold uppercase tracking-wide border border-gray-200 dark:border-neutral-600">
                                    En Taller
                                </span>
                                <a href="{{ route('work.status', [$work->id, 'listo']) }}" class="text-xs text-[#C59D5F] font-bold hover:text-[#a37f45] mt-1 block transition">
                                    Marcar Listo
                                </a>
                            @elseif($work->status == 'listo')
                                <span class="bg-[#C59D5F]/10 text-[#C59D5F] text-[10px] px-2 py-1 rounded-full font-bold uppercase tracking-wide border border-[#C59D5F]/30">
                                    Listo para recoger
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            @if($work->balance > 0)
                                <span class="text-red-600 dark:text-red-400 font-bold font-serif-display text-lg block mb-1">
                                    Bs {{ number_format($work->balance, 2) }}
                                </span>

                                @if($currentRegister)
                                    <button onclick="openPayModal(
                                        '{{ route('work.pay', $work->id) }}',
                                        '{{ $work->balance }}',
                                        '{{ $work->patient->name ?? 'Cliente Desconocido' }}',
                                        '{{ $work->receipt_number }}')"
                                        class="btn-gold text-white text-xs px-4 py-1.5 rounded-sm shadow hover:shadow-md flex items-center gap-2 ml-auto transition uppercase tracking-wide font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Cobrar
                                    </button>
                                @endif
                            @else
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold text-xs bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1 rounded border border-emerald-200 dark:border-emerald-800">
                                    PAGADO
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($work->status == 'listo' && $work->balance == 0)
                                <a href="#" onclick="confirmDelivery(event, '{{ route('work.status', [$work->id, 'entregado']) }}')" class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-sm shadow font-bold text-xs inline-flex items-center gap-2 transition uppercase tracking-wide border border-gray-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Entregar
                                </a>
                            @elseif($work->status == 'listo' && $work->balance > 0)
                                <span class="text-[10px] text-red-500 font-bold uppercase tracking-wide">
                                    ⛔ Pagar Saldo
                                </span>
                            @else
                                <span class="text-xs text-gray-300 italic">--</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="px-6 py-4 bg-white dark:bg-neutral-800 border-t border-gray-100 dark:border-neutral-700">
            {{ $pendingWorks->links() }}
        </div>

        @endif
    </div>

    {{-- MODAL GASTOS --}}
    <dialog id="modal-gasto" class="p-0 rounded-sm shadow-2xl w-full max-w-sm backdrop:bg-neutral-900/80 bg-transparent">
        <div class="bg-white dark:bg-neutral-800 overflow-hidden border-t-4 border-red-500">
            <div class="p-6">
                <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white font-serif-display">Registrar Egreso</h3>
                <form action="{{ route('cash.expense') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold mb-1 text-gray-500 uppercase">Monto</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400 font-bold">Bs</span>
                            <input type="number" name="amount" step="0.50" class="w-full border p-2 pl-10 rounded-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus:ring-red-500 outline-none font-bold" required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold mb-1 text-gray-500 uppercase">Motivo</label>
                        <input type="text" name="description" class="w-full border p-2 rounded-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus:ring-red-500 outline-none" placeholder="Ej: Compra de limpieza" required>
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100 dark:border-neutral-700">
                        <button type="button" onclick="document.getElementById('modal-gasto').close()" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-800 dark:hover:text-white transition">Cancelar</button>
                        <button class="px-6 py-2 bg-red-600 text-white rounded-sm font-bold text-sm hover:bg-red-700 shadow uppercase tracking-wide">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    {{-- MODAL COBROS --}}
    <dialog id="paymentModal" class="p-0 rounded-sm shadow-2xl border-0 w-[400px] backdrop:bg-neutral-900/80 bg-transparent">
        <div class="bg-white dark:bg-neutral-800 border-t-4 border-[#C59D5F]">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex justify-between items-center bg-gray-50 dark:bg-neutral-900">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white font-serif-display">Registrar Cobro</h3>
                <button onclick="closePayModal()" class="text-gray-400 hover:text-[#C59D5F] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="paymentForm" method="POST" class="p-6">
                @csrf

                <div class="mb-6 text-center">
                    <p class="text-sm font-bold text-gray-800 dark:text-white" id="payClientName">Cliente...</p>
                    <p class="text-xs text-gray-400 font-mono mt-1" id="payReceipt">Comprobante...</p>
                    <div class="mt-3 inline-block bg-[#C59D5F]/10 px-4 py-2 rounded border border-[#C59D5F]/30">
                        <span class="text-xs text-[#C59D5F] font-bold uppercase block mb-1">Saldo a Pagar</span>
                        <span class="text-3xl font-bold text-[#C59D5F] font-serif-display">Bs <span id="payAmountDisplay">0.00</span></span>
                    </div>
                </div>

                <input type="hidden" name="amount" id="payAmountInput">

                <div class="mb-5">
                    <label class="block text-xs font-bold mb-2 text-gray-500 uppercase tracking-wider">Método de Pago</label>
                    <select name="method" id="payMethod" onchange="togglePaymentFields()" class="w-full border p-2.5 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none text-sm font-medium">
                        <option value="Efectivo">💵 Efectivo</option>
                        <option value="QR">📱 QR / Transferencia</option>
                    </select>
                </div>

                <div id="cashFields" class="bg-gray-50 dark:bg-neutral-900 p-4 rounded-sm border border-gray-200 dark:border-neutral-700">
                    <div class="mb-3">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Monto Recibido</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-400 font-bold">Bs</span>
                            <input type="number" step="0.50" id="cashReceived" onkeyup="calculateChange()" class="w-full pl-10 p-2 border rounded-sm dark:bg-neutral-800 dark:border-neutral-600 focus:ring-1 focus-gold outline-none font-bold text-gray-900 dark:text-white" placeholder="0.00">
                        </div>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 dark:border-neutral-700 pt-2 mt-2">
                        <span class="text-xs font-bold text-gray-500 uppercase">Cambio:</span>
                        <span class="text-lg font-bold text-gray-800 dark:text-white font-mono" id="cashChange">Bs 0.00</span>
                    </div>
                </div>

                <div id="qrFields" class="hidden bg-gray-50 dark:bg-neutral-900 p-4 rounded-sm border border-gray-200 dark:border-neutral-700">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Referencia / Comprobante</label>
                    <input type="text" name="reference_number" class="w-full p-2 border rounded-sm dark:bg-neutral-800 dark:border-neutral-600 focus:ring-1 focus-gold outline-none text-sm" placeholder="Ej: 12345678">
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closePayModal()" class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-gray-300 rounded-sm font-bold text-sm hover:bg-gray-200 transition">
                        Cancelar
                    </button>
                    <button type="button" onclick="confirmPayment()" class="flex-1 px-4 py-2.5 btn-gold text-white rounded-sm font-bold text-sm shadow hover:shadow-lg transition transform hover:-translate-y-0.5 uppercase tracking-wide">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        // Variables globales
        let currentDebt = 0;

        function openPayModal(url, amount, name, receipt) {
            const modal = document.getElementById('paymentModal');
            const form = document.getElementById('paymentForm');

            // 1. Configurar datos visuales
            document.getElementById('payClientName').innerText = name;
            document.getElementById('payReceipt').innerText = 'Recibo: ' + receipt;
            document.getElementById('payAmountDisplay').innerText = parseFloat(amount).toFixed(2);

            // 2. Configurar datos del formulario
            currentDebt = parseFloat(amount);
            document.getElementById('payAmountInput').value = amount;

            // 3. Resetear campos
            document.getElementById('payMethod').value = 'Efectivo';
            document.getElementById('cashReceived').value = '';
            document.getElementById('cashChange').innerText = 'Bs 0.00';
            togglePaymentFields();

            // 4. Asignar URL
            form.action = url;

            modal.showModal();
        }

        function closePayModal() {
            document.getElementById('paymentModal').close();
        }

        function togglePaymentFields() {
            const method = document.getElementById('payMethod').value;
            const cashDiv = document.getElementById('cashFields');
            const qrDiv = document.getElementById('qrFields');
            const receivedInput = document.getElementById('cashReceived');

            if (method === 'Efectivo') {
                cashDiv.classList.remove('hidden');
                qrDiv.classList.add('hidden');
                receivedInput.required = true;
                setTimeout(() => receivedInput.focus(), 100);
            } else {
                cashDiv.classList.add('hidden');
                qrDiv.classList.remove('hidden');
                receivedInput.required = false;
            }
        }

        function calculateChange() {
            const received = parseFloat(document.getElementById('cashReceived').value) || 0;
            const change = received - currentDebt;
            const changeDisplay = document.getElementById('cashChange');

            if (change < 0) {
                changeDisplay.innerText = "Faltan Bs " + Math.abs(change).toFixed(2);
                changeDisplay.className = "text-lg font-bold text-red-500 font-mono";
            } else {
                changeDisplay.innerText = "Bs " + change.toFixed(2);
                changeDisplay.className = "text-lg font-bold text-green-600 dark:text-green-400 font-mono";
            }
        }

        function confirmPayment() {
            const method = document.getElementById('payMethod').value;
            const received = parseFloat(document.getElementById('cashReceived').value) || 0;

            if (method === 'Efectivo' && received < currentDebt) {
                Swal.fire({
                    target: document.getElementById('paymentModal'),
                    icon: 'error',
                    title: 'Monto Insuficiente',
                    text: 'El dinero recibido es menor al saldo pendiente.',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            Swal.fire({
                target: document.getElementById('paymentModal'),
                title: '¿Confirmar Cobro?',
                text: `Se registrará un ingreso de Bs ${currentDebt.toFixed(2)}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#C59D5F',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, procesar pago',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('paymentForm').submit();
                }
            });
        }

        function confirmDelivery(event, url) {
            event.preventDefault();
            Swal.fire({
                title: '¿Confirmar Entrega?',
                text: "El trabajo saldrá del inventario de pendientes.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#171717', // Negro
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, entregar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        function closeRegister(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Cerrar Caja?',
                text: "Se generará el reporte final y se cerrará caja.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c', // Rojo oscuro
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, cerrar caja'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-close-box').submit();
                    setTimeout(() => {
                        location.reload();
                    }, 4000);
                }
            });
        }
    </script>
</x-app>
