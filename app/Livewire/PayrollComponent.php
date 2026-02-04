<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\PayrollDetail;
use Carbon\Carbon;

class PayrollComponent extends Component
{
    public $selectedMonth;
    public $selectedYear;
    public $branch_id = ''; // Filtro por sucursal

    // Configuración de Multas (Esto podría ir en base de datos luego)
    public $penaltyPerLate = 50; // 50 Bs por atraso (Ejemplo)

    // Matriz de datos para edición masiva
    public $payrollData = [];

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;
        $this->loadEmployees();
    }

    public function updatedBranchId() { $this->loadEmployees(); }
    public function updatedSelectedMonth() { $this->loadEmployees(); }

    public function loadEmployees()
    {
        // Buscamos empleados activos
        $query = Employee::query()->where('is_active', true);

        if($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }

        $employees = $query->get();

        // Preparamos la estructura de datos para la vista
        $this->payrollData = [];

        foreach($employees as $emp) {
            // Buscamos si ya existe planilla guardada para este mes
            $existing = PayrollDetail::where('employee_id', $emp->id)
                        ->where('month', $this->selectedMonth)
                        ->where('year', $this->selectedYear)
                        ->first();

            if($existing) {
                // Si ya existe, cargamos lo guardado
                $this->payrollData[$emp->id] = [
                    'name' => $emp->name,
                    'position' => $emp->position,
                    'base_salary' => (float)$existing->base_salary,
                    'lates' => $existing->lates,
                    'absences' => $existing->absences,
                    'bonuses' => $existing->bonuses,
                    'saved' => true
                ];
            } else {
                // Si es nuevo mes, iniciamos en cero
                $this->payrollData[$emp->id] = [
                    'name' => $emp->name,
                    'position' => $emp->position,
                    'base_salary' => (float)$emp->base_salary,
                    'lates' => 0,
                    'absences' => 0,
                    'bonuses' => 0,
                    'saved' => false
                ];
            }
        }
    }

    public function savePayroll()
    {
        // 1. Guardar todos los datos (El código que ya tenías)
        foreach($this->payrollData as $empId => $data) {

            $dailySalary = $data['base_salary'] / 30;
            $discountLates = $data['lates'] * $this->penaltyPerLate;
            $discountAbsences = $data['absences'] * $dailySalary;
            $finalPay = $data['base_salary'] - $discountLates - $discountAbsences + $data['bonuses'];

            PayrollDetail::updateOrCreate(
                [
                    'employee_id' => $empId,
                    'month' => $this->selectedMonth,
                    'year' => $this->selectedYear
                ],
                [
                    'base_salary' => $data['base_salary'],
                    'lates' => $data['lates'],
                    'absences' => $data['absences'],
                    'discount_lates' => $discountLates,
                    'discount_absences' => $discountAbsences,
                    'bonuses' => $data['bonuses'],
                    'final_pay' => max(0, $finalPay),
                ]
            );
        }

        // 2. REDIRECCIONAR AL PDF (Nueva funcionalidad)
        // En lugar de solo mostrar una alerta, redirigimos a la vista de impresión
        return redirect()->route('payroll.print', [
            'month' => $this->selectedMonth,
            'year' => $this->selectedYear,
            'branch_id' => $this->branch_id
        ]);
    }

    public function render()
    {
        return view('livewire.payroll-component', [
            'branches' => Branch::all()
        ])->layout('layouts.app'); // <--- AGREGA ESTA FLECHA Y EL LAYOUT
    }
}
