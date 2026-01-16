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
    <div class="flex justify-between items-start mb-8 border-b border-gray-100 dark:border-neutral-700 pb-6">
        <div class="flex gap-4">
            <div class="p-3 bg-[#C59D5F]/10 rounded-full text-[#C59D5F]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-serif-display tracking-wide">Registrar Nueva Compra</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ingreso de mercadería al inventario.</p>
            </div>
        </div>
        <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-[#C59D5F] dark:text-gray-500 dark:hover:text-white flex items-center gap-2 text-xs font-bold uppercase tracking-wider transition group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    {{-- FORMULARIO --}}
    <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- SECCIÓN 1: DATOS DEL PROVEEDOR --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-neutral-800 p-6 rounded-sm shadow-xl border-t-4 border-[#C59D5F]">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-neutral-700 pb-2 font-serif-display">Datos Generales</h3>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Destino del Stock</label>
                        @if(auth()->user()->role === 'admin')
                            <select name="branch_id" class="w-full p-2.5 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none text-sm font-medium">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ auth()->user()->branch_id == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Selecciona dónde se almacenará.</p>
                        @else
                            <input type="text" value="{{ auth()->user()->branch->name }}" class="w-full p-2.5 border border-gray-200 rounded-sm bg-gray-100 dark:bg-neutral-700 text-gray-500 dark:text-gray-300 cursor-not-allowed text-sm font-medium" readonly>
                            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                        @endif
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Proveedor</label>
                        <input type="text" name="supplier" class="w-full p-2.5 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none text-sm" placeholder="Ej: Distribuidora La Paz" required>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Fecha de Compra</label>
                        <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="w-full p-2.5 border border-gray-200 rounded-sm bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white focus:ring-1 focus-gold outline-none text-sm" required>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/10 p-4 rounded-sm border border-blue-100 dark:border-blue-800/30 flex gap-3">
                        <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs text-blue-800 dark:text-blue-300 leading-tight">
                            El stock aumentará automáticamente y se actualizará el costo unitario del producto.
                        </p>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: DETALLE DE PRODUCTOS --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-sm overflow-hidden border border-gray-100 dark:border-neutral-700">
                    <div class="p-5 bg-neutral-50 dark:bg-neutral-900 border-b border-gray-200 dark:border-neutral-700 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 dark:text-white font-serif-display">Detalle de Productos</h3>
                        <button type="button" onclick="addProductRow()" class="bg-gray-200 hover:bg-gray-300 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-gray-700 dark:text-white text-xs font-bold py-2 px-4 rounded-sm flex items-center gap-1 transition uppercase tracking-wide">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Agregar Fila
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white dark:bg-neutral-800 text-gray-500 dark:text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100 dark:border-neutral-700">
                                <tr>
                                    <th class="px-4 py-3 text-left w-1/3">Producto</th>
                                    <th class="px-4 py-3 text-center w-24">Stock Actual</th>
                                    <th class="px-4 py-3 text-right w-32">Costo Unit. (Bs)</th>
                                    <th class="px-4 py-3 text-center w-24">Cantidad</th>
                                    <th class="px-4 py-3 text-right w-32">Subtotal</th>
                                    <th class="px-4 py-3 text-center w-10"></th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody" class="text-gray-700 dark:text-gray-300">
                                {{-- Filas JS --}}
                            </tbody>
                            <tfoot class="bg-neutral-50 dark:bg-neutral-900 font-bold text-gray-800 dark:text-white border-t border-gray-200 dark:border-neutral-700">
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-right text-xs uppercase tracking-widest text-gray-500">TOTAL COMPRA:</td>
                                    <td class="px-4 py-4 text-right text-xl text-[#C59D5F] font-serif-display">
                                        Bs <span id="grandTotal">0.00</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Mensaje Empty --}}
                    <div id="emptyMessage" class="p-12 text-center text-gray-400 dark:text-gray-500">
                        <div class="inline-block p-3 rounded-full bg-gray-50 dark:bg-neutral-900 mb-2">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <p class="text-sm">Agrega productos para registrar la compra.</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="btn-gold font-bold py-3 px-10 rounded-sm shadow-md transform transition hover:-translate-y-0.5 text-sm uppercase tracking-widest">
                        GUARDAR COMPRA
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- SCRIPTS --}}
    <script>
        let rowCount = 0;
        const productsList = @json($products);

        function addProductRow() {
            document.getElementById('emptyMessage').style.display = 'none';

            const tbody = document.getElementById('productsTableBody');
            const rowId = `row-${rowCount}`;

            let optionsHtml = '<option value="">Seleccione...</option>';
            productsList.forEach(p => {
                optionsHtml += `<option value="${p.id}" data-stock="${p.stock}" data-cost="${p.price_buy || 0}">${p.code} - ${p.name}</option>`;
            });

            const row = document.createElement('tr');
            row.id = rowId;
            row.className = 'border-b border-gray-50 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700/30 transition';

            row.innerHTML = `
                <td class="px-2 py-3">
                    <select name="products[${rowCount}][id]" class="w-full border border-gray-200 rounded-sm p-2 text-xs bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none cursor-pointer" onchange="updateRowInfo(this, '${rowId}')" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td class="px-2 py-3 text-center text-xs font-mono text-gray-500 dark:text-gray-400">
                    <span id="stock-${rowId}" class="bg-gray-100 dark:bg-neutral-700 px-2 py-1 rounded-sm">-</span>
                </td>
                <td class="px-2 py-3">
                    <input type="number" name="products[${rowCount}][cost]" step="0.50" class="w-full text-right border border-gray-200 rounded-sm p-2 text-xs bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none font-mono" oninput="calculateRow('${rowId}')" placeholder="0.00" required>
                </td>
                <td class="px-2 py-3">
                    <input type="number" name="products[${rowCount}][quantity]" class="w-full text-center border border-gray-200 rounded-sm p-2 text-xs bg-white dark:bg-neutral-800 dark:border-neutral-600 dark:text-white focus:ring-1 focus-gold outline-none font-bold text-[#C59D5F]" oninput="calculateRow('${rowId}')" placeholder="1" required>
                </td>
                <td class="px-2 py-3 text-right font-bold text-gray-800 dark:text-white text-sm">
                    Bs <span id="subtotal-${rowId}">0.00</span>
                </td>
                <td class="px-2 py-3 text-center">
                    <button type="button" onclick="removeRow('${rowId}')" class="text-gray-400 hover:text-red-500 transition p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            `;

            tbody.appendChild(row);
            rowCount++;
        }

        function updateRowInfo(select, rowId) {
            const selectedOption = select.options[select.selectedIndex];
            const stock = selectedOption.getAttribute('data-stock');
            const lastCost = selectedOption.getAttribute('data-cost');

            document.getElementById(`stock-${rowId}`).innerText = stock || '-';

            const costInput = document.querySelector(`#${rowId} input[name*="[cost]"]`);
            if(costInput.value == '' || costInput.value == 0) {
                costInput.value = lastCost;
            }

            calculateRow(rowId);
        }

        function calculateRow(rowId) {
            const cost = parseFloat(document.querySelector(`#${rowId} input[name*="[cost]"]`).value) || 0;
            const qty = parseFloat(document.querySelector(`#${rowId} input[name*="[quantity]"]`).value) || 0;
            const subtotal = cost * qty;

            document.getElementById(`subtotal-${rowId}`).innerText = subtotal.toFixed(2);
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let total = 0;
            const subtotals = document.querySelectorAll('[id^="subtotal-"]');
            subtotals.forEach(span => {
                total += parseFloat(span.innerText);
            });
            document.getElementById('grandTotal').innerText = total.toFixed(2);
        }

        function removeRow(rowId) {
            document.getElementById(rowId).remove();
            calculateGrandTotal();

            if(document.getElementById('productsTableBody').children.length === 0) {
                document.getElementById('emptyMessage').style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            addProductRow();
        });
    </script>
</x-app>
