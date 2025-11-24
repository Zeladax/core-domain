<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('dni', 8)->nullable();
            $table->timestamps();

            // Índices únicos para evitar duplicados
            $table->unique(['email', 'dni']);
            $table->index('email');
            $table->index('dni');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
