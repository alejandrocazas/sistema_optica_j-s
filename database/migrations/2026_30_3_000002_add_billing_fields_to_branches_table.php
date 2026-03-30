<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            // true = Ya pagó la instalación | false = Debe instalación
            $table->boolean('installation_paid')->default(false)->after('name');

            // Fecha del próximo pago mensual (puede ser nula si aún no arranca)
            $table->date('next_payment_date')->nullable()->after('installation_paid');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['installation_paid', 'next_payment_date']);
        });
    }
};
