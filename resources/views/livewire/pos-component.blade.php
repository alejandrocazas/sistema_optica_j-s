<div>
    {{-- ESTILOS "GOLD & BLACK" --}}
    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }

        .btn-gold {
            background: linear-gradient(135deg, #C59D5F 0%, #a37f45 100%);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d6ad6d 0%, #b89050 100%);
            box-shadow: 0 4px 6px -1px rgba(197, 157, 95, 0.4);
            transform: translateY(-1px);
        }

        /* Scrollbar personalizado oscuro */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #C59D5F; }
    </style>

    <div class="h-screen flex flex-col md:flex-row bg-gray-100 dark:bg-neutral-900 overflow-hidden font-sans">

        {{-- SECCIÓN IZQUIERDA: LISTA DE PRODUCTOS --}}
        <div class="w-full md:w-2/3 flex flex-col p-4 gap-4">

            {{-- BUSCADOR --}}
            <div class="bg-white dark:bg-neutral-800 p-4 rounded-sm shadow-md border-t-4 border-[#C59D5F]">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input wire:model.live="searchProduct" type="text"
                           class="w-full py-3 pl-10 pr-4 bg-gray-50 dark:bg-neutral-700 border-gray-200 dark:border-neutral-600 rounded-sm focus:ring-2 focus:ring-[#C59D5F] focus:border-transparent dark:text-white shadow-sm transition-all"
                           placeholder="Buscar producto por nombre o código..." autofocus>
                </div>
            </div>

            {{-- TABLA DE PRODUCTOS --}}
            <div class="flex-1 overflow-y-auto bg-white dark:bg-neutral-800 rounded-sm shadow-md">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-neutral-900 text-[#C59D5F] text-xs uppercase sticky top-0 z-10 tracking-wider">
                        <tr>
                            <th class="p-3">Imagen</th>
                            <th class="p-3">Descripción</th>
                            <th class="p-3 text-center">Código</th>
                            <th class="p-3 text-center">Stock</th>
                            <th class="p-3 text-right">Precio</th>
                            <th class="p-3 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-neutral-700">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50 transition group">
                            <td class="p-3 w-16">
                                @if($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}" class="w-10 h-10 object-cover rounded border border-gray-200 dark:border-neutral-600">
                                @else
                                    <div class="w-10 h-10 bg-gray-100 dark:bg-neutral-700 rounded flex items-center justify-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="p-3">
                                <p class="font-bold text-gray-800 dark:text-white">{{ $product->name }}</p>
                                <p class="text-[10px] text-[#C59D5F] uppercase tracking-wide">{{ Str::limit($product->category->name ?? '', 20) }}</p>
                            </td>
                            <td class="p-3 text-center font-mono text-xs text-gray-500">{{ $product->code }}</td>
                            <td class="p-3 text-center">
                                @php $stockReal = $product->stock_actual; @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $stockReal < 10 ? 'border-red-500 text-red-500 bg-red-50 dark:bg-red-900/10' : 'border-green-500 text-green-500 bg-green-50 dark:bg-green-900/10' }}">
                                    {{ $stockReal }}
                                </span>
                            </td>
                            <td class="p-3 text-right font-bold text-gray-900 dark:text-white">Bs {{ number_format($product->price_sell, 2) }}</td>
                            <td class="p-3 text-center">
                                <button wire:click="addToCart({{ $product->id }})" class="btn-gold px-3 py-1.5 rounded text-xs font-bold shadow-sm uppercase tracking-wide">
                                    Agregar
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-gray-400 dark:text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    @if($searchProduct) No se encontraron productos. @else Usa el buscador para comenzar. @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SECCIÓN DERECHA: CARRITO Y PAGO (NEGRO Y DORADO) --}}
        <div class="w-full md:w-1/3 bg-neutral-900 border-l border-gray-800 flex flex-col h-full shadow-2xl relative z-20">

            {{-- HEADER DEL CARRITO --}}
            <div class="p-4 bg-neutral-800 border-b border-gray-700 shadow-md">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-8 bg-[#C59D5F]"></div>
                        <div>
                            <h2 class="text-white font-bold text-lg leading-none">VENTA ACTUAL</h2>
                            <span class="text-[10px] text-[#C59D5F] uppercase tracking-widest">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                    <span class="text-[10px] font-mono bg-neutral-700 text-gray-300 px-2 py-1 rounded">
                        {{ date('d/m/Y') }}
                    </span>
                </div>

                {{-- SELECCIÓN DE CLIENTE --}}
                @if($selectedPatientName)
                    <div class="bg-neutral-700/50 p-3 rounded border border-[#C59D5F]/30">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Cliente</p>
                                <p class="font-bold text-white truncate max-w-[150px]">{{ $selectedPatientName }}</p>
                            </div>
                            <button wire:click="$set('selectedPatientName', null); $set('patient_id', null)" class="text-xs text-red-400 hover:text-red-300 underline">Cambiar</button>
                        </div>

                        {{-- CHECKBOX CONSULTA (C/S o S/C) --}}
                        <div class="flex items-center gap-3 mt-2 border-t border-gray-700 pt-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="withConsultation" class="sr-only peer">
                                <div class="relative w-9 h-5 bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#C59D5F] rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C59D5F]"></div>
                                <span class="ms-2 text-xs font-medium text-gray-300">
                                    {{ $withConsultation ? 'CON CONSULTA (C/S)' : 'SIN CONSULTA (S/C)' }}
                                </span>
                            </label>
                        </div>

                        <button onclick="document.getElementById('modal-receta').showModal()" class="w-full mt-3 bg-neutral-800 hover:bg-neutral-600 text-[#C59D5F] border border-[#C59D5F] text-xs font-bold py-1.5 px-2 rounded transition flex justify-center items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            INGRESAR RECETA
                        </button>
                    </div>
                @else
                    <div class="relative w-full">
                        <input wire:model.live="searchPatient" type="text" placeholder="Buscar Cliente..." class="w-full p-2 bg-neutral-700 text-white rounded text-sm border border-gray-600 focus:border-[#C59D5F] focus:ring-1 focus:ring-[#C59D5F] placeholder-gray-400 outline-none" autocomplete="off">
                        @if(strlen($searchPatient) > 0)
                            <div class="absolute z-50 w-full mt-1 bg-neutral-800 rounded shadow-xl border border-gray-600 max-h-48 overflow-y-auto">
                                @forelse($patients as $patient)
                                    <div wire:click="selectPatient({{ $patient->id }}, '{{ $patient->name }}')" class="px-4 py-2 hover:bg-[#C59D5F] hover:text-white cursor-pointer border-b border-gray-700 last:border-0 transition-colors text-sm group">
                                        <p class="font-bold text-gray-200 group-hover:text-white">{{ $patient->name }}</p>
                                        <p class="text-xs text-gray-500 group-hover:text-white/80">CI: {{ $patient->ci ?? 'S/N' }}</p>
                                    </div>
                                @empty
                                    <div class="px-4 py-3 text-center text-sm text-gray-400">
                                        No encontrado. <a href="{{ route('patients.create') }}" target="_blank" class="text-[#C59D5F] font-bold hover:underline">¿Crear?</a>
                                    </div>
                                @endforelse
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- LISTA DE ITEMS CARRITO --}}
            <div class="flex-1 overflow-y-auto p-4 bg-neutral-900">
                @forelse($cart as $id => $item)
                <div wire:key="cart-item-{{ $item['id'] }}" class="bg-neutral-800 p-3 mb-2 rounded border border-gray-700 flex justify-between items-center group hover:border-[#C59D5F]/50 transition">
                    <div class="flex-1 pr-2">
                        <h4 class="font-bold text-gray-200 text-sm leading-tight">{{ $item['name'] }}</h4>
                        <div class="text-[10px] text-[#C59D5F] mt-1 font-mono">
                            {{ $item['quantity'] }} x Bs {{ number_format($item['price'], 2) }}
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <div class="font-bold text-white text-base">Bs {{ number_format($item['subtotal'], 2) }}</div>
                        <div class="flex items-center bg-neutral-900 rounded border border-gray-700 overflow-hidden">
                            <button wire:click="decreaseQuantity({{ $item['id'] }})" class="px-2 py-0.5 hover:bg-gray-700 text-gray-400 hover:text-white transition">-</button>
                            <span class="px-2 text-xs font-bold text-white min-w-[20px] text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="addToCart({{ $item['id'] }})" class="px-2 py-0.5 hover:bg-gray-700 text-[#C59D5F] transition">+</button>
                        </div>
                    </div>
                    <button wire:click="removeFromCart({{ $item['id'] }})" class="ml-2 text-gray-600 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-gray-600 opacity-50">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <p class="text-sm uppercase tracking-widest">Carrito Vacío</p>
                    </div>
                @endforelse
            </div>

            {{-- PIE DE PÁGINA: TOTALES Y PAGO --}}
            <div class="bg-neutral-800 p-4 border-t border-[#C59D5F] shadow-[0_-4px_10px_rgba(0,0,0,0.5)] z-30">

                {{-- FECHA Y OBSERVACIÓN --}}
                <div class="flex gap-2 mb-3">
                    <input wire:model="delivery_date" type="datetime-local" class="w-1/2 p-1.5 text-xs border border-gray-600 rounded bg-neutral-700 text-white focus:border-[#C59D5F] outline-none">
                    <textarea wire:model="observations" rows="1" class="w-1/2 p-1.5 text-xs border border-gray-600 rounded bg-neutral-700 text-white focus:border-[#C59D5F] outline-none" placeholder="Observaciones..."></textarea>
                </div>

                {{-- CÁLCULOS: SUBTOTAL, DESCUENTO, TOTAL --}}
                <div class="space-y-1 mb-3 text-sm">
                    <div class="flex justify-between text-gray-400">
                        <span>Subtotal:</span>
                        <span>Bs {{ number_format($total, 2) }}</span>
                    </div>

                    {{-- CAMPO DESCUENTO --}}
                    <div class="flex justify-between items-center text-[#C59D5F]">
                        <span class="text-xs uppercase font-bold">Descuento:</span>
                        <div class="flex items-center gap-1 border-b border-[#C59D5F]/50 w-24">
                            <span class="text-xs">- Bs</span>
                            <input wire:model.live="discount" type="number" min="0" step="0.50"
                                   class="w-full bg-transparent text-right font-bold text-[#C59D5F] focus:outline-none p-0 text-sm placeholder-[#C59D5F]/50"
                                   placeholder="0.00">
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-xl pt-2 border-t border-gray-700 mt-1">
                        <span class="font-bold text-white">TOTAL:</span>
                        @php
                            // Lógica visual del descuento
                            $discountVal = is_numeric($discount) ? (float)$discount : 0;
                            $finalTotal = max(0, $total - $discountVal);
                        @endphp
                        <span class="font-bold text-white">Bs {{ number_format($finalTotal, 2) }}</span>
                    </div>
                </div>

                {{-- MÉTODO DE PAGO --}}
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div>
                        <select wire:model.live="paymentMethod" class="w-full p-2 bg-neutral-700 border-gray-600 text-white rounded text-xs focus:ring-[#C59D5F]">
                            <option value="Efectivo">Efectivo</option>
                            <option value="QR">QR / Transf.</option>
                            <option value="Tarjeta">Tarjeta</option>
                        </select>
                    </div>
                    <div>
                        <input wire:model.live="amountPaid" type="number" step="0.50"
                               class="w-full p-2 bg-neutral-700 border-gray-600 text-white rounded text-xs text-right focus:border-[#C59D5F] font-bold"
                               placeholder="Monto Recibido">
                    </div>
                </div>

                @if($paymentMethod == 'QR' || $paymentMethod == 'Tarjeta')
                    <input wire:model="paymentReference" type="text" class="w-full mb-3 p-2 bg-neutral-700 border-gray-600 text-white rounded text-xs font-mono uppercase" placeholder="Nro. Comprobante">
                @endif

                {{-- SALDO Y CAMBIO --}}
                @php
                    $pagado = (float)$amountPaid;
                    $saldo = $finalTotal - $pagado;
                    $cambio = 0;
                    if($saldo < 0) { $cambio = abs($saldo); $saldo = 0; }
                @endphp

                <div class="flex justify-between text-xs mb-4 text-gray-400">
                    <span>Saldo: <b class="{{ $saldo > 0 ? 'text-red-400' : 'text-gray-500' }}">Bs {{ number_format($saldo, 2) }}</b></span>
                    @if($cambio > 0)
                        <span class="text-green-400">Cambio: <b>Bs {{ number_format($cambio, 2) }}</b></span>
                    @endif
                </div>

                <button onclick="confirmSale()" class="btn-gold w-full font-bold py-3 rounded shadow-lg text-sm uppercase tracking-widest flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    CONFIRMAR VENTA
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL RECETA (REDDISEÑADO EN OSCURO Y DORADO) --}}
    <dialog id="modal-receta" class="p-0 rounded-lg shadow-2xl w-[600px] backdrop:bg-black/80 bg-transparent" wire:ignore.self>
        <div class="bg-neutral-900 border border-gray-700 rounded-lg overflow-hidden">
            {{-- Encabezado Modal --}}
            <div class="bg-neutral-800 px-4 py-3 flex justify-between items-center border-b border-[#C59D5F]/30">
                <h3 class="font-bold text-lg flex items-center gap-2 text-[#C59D5F]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Registrar Receta Externa
                </h3>
                <button type="button" onclick="document.getElementById('modal-receta').close()" class="text-gray-400 hover:text-white text-2xl transition">&times;</button>
            </div>

            <div class="p-6 bg-neutral-900 text-gray-200">

                {{-- ENCABEZADOS TABLA --}}
                <div class="grid grid-cols-6 gap-2 mb-2 text-center text-[10px] font-bold uppercase text-[#C59D5F] tracking-wider">
                    <div class="text-left pl-2 text-gray-500">Ojo</div>
                    <div>Esfera</div>
                    <div>Cilindro</div>
                    <div>Eje</div>
                    <div>Adición</div>
                    <div>D.I.P</div>
                </div>

                {{-- FILA OJO DERECHO (OD) --}}
                <div class="grid grid-cols-6 gap-2 mb-3 items-center">
                    <div class="font-bold text-[#C59D5F] text-sm pl-2">OD</div>

                    <input wire:model="od_esfera" type="number" step="0.25" placeholder="0.00"
                        class="w-full p-2 text-center border rounded text-sm bg-neutral-800 border-gray-700 text-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none">

                    <input wire:model="od_cilindro" type="number" step="0.25" placeholder="0.00"
                        class="w-full p-2 text-center border rounded text-sm bg-neutral-800 border-gray-700 text-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none">

                    <input wire:model="od_eje" type="number" placeholder="0°"
                        class="w-full p-2 text-center border rounded text-sm bg-neutral-800 border-gray-700 text-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none">

                    <input wire:model="add_od" type="number" step="0.25" placeholder="+0.00"
                        class="w-full p-2 text-center border rounded text-sm bg-neutral-800 border-gray-700 text-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none">

                    <input wire:model="dip" type="number" placeholder="mm"
                        class="w-full p-2 text-center border rounded text-sm bg-neutral-800 border-gray-700 text-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none">
                </div>

                {{-- FILA OJO IZQUIERDO (OI) --}}
                <div class="grid grid-cols-6 gap-2 mb-4 items-center">
                    <div class="font-bold text-white text-sm pl-2">OI</div>

                    <input wire:model="oi_esfera" type="number" step="0.25" placeholder="0.00"
                        class="w-full p-2 text-center border rounded text-sm bg-neutral-800 border-gray-700 text-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none">

                    <input wire:model="oi_cilindro" type="number" step="0.25" placeholder="0.00"
                        class="w-full p-2 text-center border rounded text-sm bg-neutral-800 border-gray-700 text-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none">

                    <input wire:model="oi_eje" type="number" placeholder="0°"
                        class="w-full p-2 text-center border rounded text-sm bg-neutral-800 border-gray-700 text-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none">

                    <input wire:model="add_oi" type="number" step="0.25" placeholder="+0.00"
                        class="w-full p-2 text-center border rounded text-sm bg-neutral-800 border-gray-700 text-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none">

                    <div class="text-center text-xs text-gray-600">-</div>
                </div>

                {{-- DIAGNÓSTICO Y OBSERVACIONES --}}
                <div class="grid grid-cols-1 gap-3 mb-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Diagnóstico (Opcional)</label>
                        <input wire:model="diagnostico" type="text" class="w-full mt-1 p-2 text-sm border rounded bg-neutral-800 border-gray-700 text-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none" placeholder="Ej: Miopía leve...">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Observaciones / Tipo de Lente</label>
                        <textarea wire:model="observaciones_receta" rows="2" class="w-full mt-1 p-2 text-sm border rounded bg-neutral-800 border-gray-700 text-white focus:ring-1 focus:ring-[#C59D5F] focus:border-[#C59D5F] outline-none" placeholder="Ej: Material fotocromático..."></textarea>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-800">
                    <button onclick="document.getElementById('modal-receta').close()" class="px-4 py-2 text-gray-400 hover:text-white bg-transparent border border-gray-700 hover:bg-gray-800 rounded font-bold text-xs uppercase tracking-wide transition">
                        Cancelar
                    </button>
                    <button wire:click="savePrescription" class="px-6 py-2 btn-gold rounded font-bold text-xs uppercase tracking-wide shadow-md">
                        Guardar Receta
                    </button>
                </div>
            </div>
        </div>
    </dialog>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-prescription-modal', () => document.getElementById('modal-receta').close());

            Livewire.on('sale-success', (event) => {
                Swal.fire({
                    title: '¡Venta Exitosa!',
                    icon: 'success',
                    background: '#1a1a1a',
                    color: '#fff',
                    confirmButtonColor: '#C59D5F',
                    timer: 1500,
                    showConfirmButton: false
                });

                let saleId = null;
                if(Array.isArray(event) && event[0].saleId) saleId = event[0].saleId;
                else if (event.saleId) saleId = event.saleId;
                else if (event.detail && event.detail.saleId) saleId = event.detail.saleId;

                if(saleId) setTimeout(() => window.open('/ventas/' + saleId + '/imprimir', '_blank'), 1000);
            });
        });

        function confirmSale() {
            Swal.fire({
                title: '¿Procesar Venta?',
                text: "Verifica los datos antes de continuar.",
                icon: 'warning',
                background: '#1a1a1a',
                color: '#fff',
                showCancelButton: true,
                confirmButtonColor: '#C59D5F',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SÍ, VENDER',
                cancelButtonText: 'CANCELAR'
            }).then((result) => {
                if (result.isConfirmed) Livewire.dispatch('confirmSale');
            })
        }
    </script>
</div>
