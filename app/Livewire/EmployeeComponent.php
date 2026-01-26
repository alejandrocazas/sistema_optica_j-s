<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use App\Models\Branch;

class EmployeeComponent extends Component
{
    use WithPagination;

    // Campos del Formulario
    public $name, $ci, $position, $base_salary, $branch_id, $hiring_date;
    public $employeeId;
    public $isOpen = false;

    // Filtros
    public $search = '';
    public $perPage = 10;

    public function render()
    {
        $employees = Employee::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('ci', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.employee-component', [
            'employees' => $employees,
            'branches' => Branch::all()
        ])->layout('layouts.app');
        // NOTA: Si usas <x-app> en tus vistas normales,
        // asegúrate que layouts.app tenga la misma estructura.
    }

    // --- FUNCIONES DEL MODAL ---
    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function openModal() { $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; }

    private function resetInputFields()
    {
        $this->name = '';
        $this->ci = '';
        $this->position = '';
        $this->base_salary = '';
        $this->branch_id = '';
        $this->hiring_date = '';
        $this->employeeId = null;
    }

    // --- GUARDAR / ACTUALIZAR ---
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'ci' => 'required',
            'position' => 'required',
            'base_salary' => 'required|numeric',
            'branch_id' => 'required'
        ]);

        Employee::updateOrCreate(['id' => $this->employeeId], [
            'name' => $this->name,
            'ci' => $this->ci,
            'position' => $this->position,
            'base_salary' => $this->base_salary,
            'branch_id' => $this->branch_id,
            'hiring_date' => $this->hiring_date ? $this->hiring_date : null,
            'is_active' => true
        ]);

        $this->closeModal();
        $this->resetInputFields();
        // DISPARAR ALERTA DE ÉXITO
        this->dispatch('swal', [
        'title' => '¡Excelente!',
        'text' => 'El empleado se ha guardado correctamente.',
        'icon' => 'success'
        ]);
    }

    // --- EDITAR ---
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $this->employeeId = $id;
        $this->name = $employee->name;
        $this->ci = $employee->ci;
        $this->position = $employee->position;
        $this->base_salary = $employee->base_salary;
        $this->branch_id = $employee->branch_id;
        $this->hiring_date = $employee->hiring_date;

        $this->openModal();
    }

    // --- ELIMINAR (DESACTIVAR) ---
    public function delete($id)
    {
        $employee = Employee::find($id);
        $employee->is_active = false; // Soft delete lógico
        $employee->save();
        // DISPARAR ALERTA DE ELIMINACIÓN
        $this->dispatch('swal', [
        'title' => 'Desactivado',
        'text' => 'El empleado ha sido dado de baja.',
        'icon' => 'warning'
    ]);
    }
}
