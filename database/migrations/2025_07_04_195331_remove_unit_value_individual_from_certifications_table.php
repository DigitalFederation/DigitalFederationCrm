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
        Schema::table('certification', function (Blueprint $table) {
            $table->dropColumn('unit_value_individual');
        });

        // Also add unit_value_federation column if it doesn't exist
        Schema::table('certification', function (Blueprint $table) {
            if (! Schema::hasColumn('certification', 'unit_value_federation')) {
                $table->decimal('unit_value_federation', 10, 2)->nullable()->after('unit_value_entity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification', function (Blueprint $table) {
            $table->decimal('unit_value_individual', 10, 2)->nullable()->after('unit_value');
        });

        Schema::table('certification', function (Blueprint $table) {
            $table->dropColumn('unit_value_federation');
        });
    }
};
