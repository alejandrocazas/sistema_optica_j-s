<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración (Agregar columnas).
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {

            // 1. Campo para el Descuento
            // (Lo ponemos después del total para mantener orden)
            if (!Schema::hasColumn('sales', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0)->after('total');
            }

            // 2. Campo para saber si hubo Consulta (C/S o S/C)
            if (!Schema::hasColumn('sales', 'has_consultation')) {
                $table->boolean('has_consultation')->default(false)->after('patient_id');
            }

            // 3. Fecha de Entrega
            if (!Schema::hasColumn('sales', 'delivery_date')) {
                $table->dateTime('delivery_date')->nullable()->after('payment_status');
            }

            // 4. Observaciones de la venta
            if (!Schema::hasColumn('sales', 'observations')) {
                $table->text('observations')->nullable()->after('delivery_date');
            }
        });
    }

    /**
     * Revertir la migración (Borrar columnas si deshacemos).
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['discount', 'has_consultation', 'delivery_date', 'observations']);
        });
    }
};
