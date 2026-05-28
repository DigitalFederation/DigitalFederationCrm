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
        Schema::table('certifications_slot_prices', function (Blueprint $table) {
            $table->decimal('unit_price', 12, 2)->change();
            $table->decimal('tax_value', 12, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certifications_slot_prices', function (Blueprint $table) {
            $table->decimal('unit_price', 12, 3)->change();
            $table->decimal('tax_value', 12, 3)->change();
        });
    }
};
