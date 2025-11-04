<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auditor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('audit_date');
            $table->text('summary')->nullable();
            $table->text('recommendation')->nullable();
            $table->string('path_report')->nullable();
            $table->string('status')->default('pending')->index();
            $table->morphs('auditable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
