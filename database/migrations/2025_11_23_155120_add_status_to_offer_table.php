<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use IncadevUns\CoreDomain\Enums\OfferStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->enum('status', OfferStatus::values())
                ->default(OfferStatus::Active->value)
                ->after('available_positions');
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
