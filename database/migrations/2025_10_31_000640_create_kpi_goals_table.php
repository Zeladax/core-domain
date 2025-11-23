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
        Schema::create('kpi_goals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nombre del KPI
            $table->string('display_name'); // Nombre para mostrar
            $table->decimal('goal_value', 5, 2)->default(0); // Meta (ej: 85.00%)
            $table->decimal('current_value', 5, 2)->default(0); // Valor actual calculado
            $table->decimal('previous_value', 5, 2)->default(0); // Valor del mes anterior para calcular tendencia
            $table->decimal('trend', 5, 2)->default(0); // Tendencia (+2.4%, -1.8%)
            $table->string('status')->default('Requiere atención'); // Cambiado de enum a string por compatibilidad
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_goals');
    }
};
