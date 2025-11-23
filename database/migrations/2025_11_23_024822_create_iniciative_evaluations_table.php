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
        Schema::create('iniciative_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iniciative_id')->nullable()->constrained('iniciatives')->nullOnDelete();
            $table->foreignId('evaluator_user')->nullable()->constrained('users')->nullOnDelete();
            $table->string('summary');
            $table->decimal('score', 5, 2);
            $table->foreignId('document_id')->nullable()->constrained('strategic_documents')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iniciative_evaluations');
    }
};
