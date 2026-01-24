<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique(); // Nro Comprobante

            // Relaciones
            $table->foreignId('user_id')->constrained(); // Vendedor
            $table->foreignId('patient_id')->nullable()->constrained(); // Cliente
            $table->foreignId('branch_id')->nullable()->constrained(); // Sucursal (Importante para el recibo)

            // --- CAMPOS NUEVOS SOLICITADOS ---
            $table->boolean('has_consultation')->default(false); // Check de Consulta (C/S o S/C)
            $table->dateTime('delivery_date')->nullable();       // Fecha de entrega
            $table->text('observations')->nullable();            // Observaciones de la venta
            // ---------------------------------

            // Detalles Económicos
            $table->decimal('total', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);      // Campo para el Descuento
            $table->decimal('paid_amount', 10, 2)->default(0);   // A Cuenta
            $table->decimal('balance', 10, 2)->default(0);       // Saldo

            $table->string('payment_method')->default('Efectivo'); // Método de pago

            // Estados
            $table->enum('status', ['pendiente', 'laboratorio', 'listo', 'entregado', 'cancelado'])->default('laboratorio');
            $table->enum('payment_status', ['pendiente', 'parcial', 'pagado'])->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
