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
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->nullable()->constrained('posts')->onDelete('cascade');
            $table->integer('messages_received')->default(0);
            $table->integer('pre_registrations')->default(0);
            $table->decimal('intention_percentage', 5, 2)->default(0);
            $table->integer('total_reach')->default(0);
            $table->integer('total_interactions')->default(0);
            $table->decimal('ctr_percentage', 5, 2)->default(0);
            $table->integer('likes')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('private_messages')->default(0);
            $table->integer('expected_enrollments')->default(0);
            $table->decimal('cpa_cost', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metrics');
    }
};
