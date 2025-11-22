<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Estándares de Calidad (Lo tuyo)
        Schema::create('quality_standards', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Antes title
            $table->string('category'); // Ej: "ISO 9001", "Infraestructura", "SINEACE"
            $table->text('description')->nullable();
            $table->decimal('target_score', 3, 2); // La meta (4.5)
            $table->json('target_roles')->nullable(); // Quién evalúa
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Evaluaciones de Estándares (Los votos)
        Schema::create('standard_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quality_standard_id')->constrained('quality_standards')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('score'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['quality_standard_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standard_ratings');
        Schema::dropIfExists('quality_standards');
    }
};
