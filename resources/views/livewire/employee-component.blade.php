<div>
    {{-- EL DIV PADRE ES OBLIGATORIO EN LIVEWIRE --}}

    <style>
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; }
        .border-gold { border-color: #C59D5F; }
        .font-serif-display { font-family: 'Playfair Display', serif; }

        .btn-gold {
            background: linear-gradient(135deg, #C59D5F 0%, #a37f45 100%);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d6ad6d 0%, #b89050 100%);
            box-shadow: 0 4px 12px rgba(197, 157, 95, 0.4);
        }
    </style>

    <div class="py-6 px-4 sm:px-6 lg:px-8">

        {{-- ENCABEZADO --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4 border-b border-gray-200 dark:border-gray-700 pb-6">
            <div class="flex items-center gap-3">
                <div class="bg-gray-900 dark:bg-gray-700 p-3 rounded-lg text-[#C59D5F] shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase font-serif-display tracking-wide">
                        Personal
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Gestión de contratos y datos de empleados</p>
                </div>
            </div>

            <button wire:click="create" class="btn-gold font-bold py-2.5 px-6 rounded shadow-md flex items-center gap-2 uppercase text-xs tracking-wider">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Nuevo Empleado
            </button>
        </div>

        {{-- CONTENEDOR PRINCIPAL --}}
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg border-t-4 border-[#C59D5F] overflow-hidden">

            {{-- BARRA DE HERRAMIENTAS --}}
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Mostrar</span>
                    <select wire:model.live="perPage" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm rounded focus:ring-[#C59D5F] focus:border-[#C59D5F]">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <div class="relative w-full sm:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live="search"
                           class="pl-10 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-[#C59D5F] focus:border-[#C59D5F] sm:text-sm"
                           placeholder="Buscar por Nombre o CI...">
                </div>
            </div>

            {{-- TABLA --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-900 text-[#C59D5F]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Cédula</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Empleado</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Sucursal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Cargo</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Sueldo Base</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($employees as $employee)
                        <tr class="hover:bg-[#C59D5F]/10 dark:hover:bg-[#C59D5F]/5 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500 dark:text-gray-400">
                                {{ $employee->ci }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $employee->name }}</div>
                                <div class="text-xs text-gray-500">Ingreso: {{ $employee->hiring_date ? \Carbon\Carbon::parse($employee->hiring_date)->format('d/m/Y') : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $employee->branch->name ?? 'Sin Asignar' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                    {{ $employee->position }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-[#C59D5F] font-mono">
                                {{ number_format($employee->base_salary, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button wire:click="edit({{ $employee->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3 transition transform hover:scale-110" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button wire:click="delete({{ $employee->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition transform hover:scale-110" onclick="confirm('¿Estás seguro de desactivar este empleado?') || event.stopImmediatePropagation()" title="Desactivar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-full mb-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <p>No se encontraron empleados registrados.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                {{ $employees->links() }}
            </div>
        </div>

        {{-- MODAL CREAR / EDITAR --}}
        @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200 dark:border-gray-700">

                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white uppercase tracking-wide border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                                    {{ $employeeId ? 'Editar Empleado' : 'Registrar Nuevo Empleado' }}
                                </h3>
                                <div class="grid grid-cols-1 gap-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nombre Completo</label>
                                        <input type="text" wire:model="name" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-[#C59D5F] focus:border-[#C59D5F] sm:text-sm">
                                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">C.I. / Documento</label>
                                            <input type="text" wire:model="ci" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-[#C59D5F] focus:border-[#C59D5F] sm:text-sm">
                                            @error('ci') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Cargo</label>
                                            <input type="text" wire:model="position" placeholder="Ej: Vendedor" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-[#C59D5F] focus:border-[#C59D5F] sm:text-sm">
                                            @error('position') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Sucursal</label>
                                            <select wire:model="branch_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-[#C59D5F] focus:border-[#C59D5F] sm:text-sm">
                                                <option value="">Seleccione...</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('branch_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Sueldo Base (Bs)</label>
                                            <input type="number" step="0.01" wire:model="base_salary" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-[#C59D5F] focus:border-[#C59D5F] sm:text-sm">
                                            @error('base_salary') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Fecha de Ingreso</label>
                                        <input type="date" wire:model="hiring_date" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-[#C59D5F] focus:border-[#C59D5F] sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-600">
                        <button wire:click="store" type="button" class="btn-gold w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Guardar
                        </button>
                        <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-500 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
