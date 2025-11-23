<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('iniciatives', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('plan_id')->nullable()->constrained('strategic_plans')->nullOnDelete();
            $table->string('summary');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('estimated_impact');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iniciatives');
    }
};
