<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
    // 1. Tabla de Empleados (Personal)
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->foreignId('branch_id')->constrained(); // Pertenece a una sucursal
        $table->string('name');
        $table->string('ci')->nullable();
        $table->string('position'); // Cargo: Vendedor, Optómetra, Limpieza
        $table->decimal('base_salary', 10, 2); // Sueldo Base (Ej: 2500)
        $table->date('hiring_date')->nullable(); // Fecha contratación
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    // 2. Tabla de Detalles de Planilla (Historial de Pagos)
    Schema::create('payroll_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained();
        $table->integer('month'); // 1 al 12
        $table->integer('year');  // 2026

        // Novedades
        $table->integer('lates')->default(0); // Cantidad de Atrasos
        $table->integer('absences')->default(0); // Cantidad de Faltas (Días)

        // Dineros
        $table->decimal('base_salary', 10, 2); // Sueldo base en ese momento
        $table->decimal('discount_lates', 10, 2)->default(0); // Monto descontado por atrasos
        $table->decimal('discount_absences', 10, 2)->default(0); // Monto descontado por faltas
        $table->decimal('bonuses', 10, 2)->default(0); // Bonos (Opcional)
        $table->decimal('final_pay', 10, 2); // Líquido Pagable

        $table->text('notes')->nullable();
        $table->timestamps();
    });
    }
};
