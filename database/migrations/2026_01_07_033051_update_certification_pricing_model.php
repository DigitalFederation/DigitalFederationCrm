<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('certification', function (Blueprint $table) {
            // Add new pricing fields
            $table->decimal('digital_price', 10, 2)->nullable()->after('tax_percentage');
            $table->decimal('digital_plus_card_price', 10, 2)->nullable()->after('digital_price');
        });

        // Migrate existing data: copy unit_value_entity to digital_price
        DB::table('certification')
            ->whereNotNull('unit_value_entity')
            ->update(['digital_price' => DB::raw('unit_value_entity')]);

        // Also copy unit_value if unit_value_entity is null but unit_value exists
        DB::table('certification')
            ->whereNull('unit_value_entity')
            ->whereNotNull('unit_value')
            ->update(['digital_price' => DB::raw('unit_value')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification', function (Blueprint $table) {
            $table->dropColumn(['digital_price', 'digital_plus_card_price']);
        });
    }
};
