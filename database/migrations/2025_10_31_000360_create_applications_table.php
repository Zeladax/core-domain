<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained()->onDelete('cascade');
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->string('cv_path');
            $table->enum('status', ['pending', 'under_review', 'shortlisted', 'rejected', 'hired', 'withdrawn'])->default('pending');
            $table->timestamps();

            // Un applicant solo puede postular una vez por oferta
            $table->unique(['offer_id', 'applicant_id']);

            // Índices para búsquedas eficientes
            $table->index(['offer_id', 'status']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
