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
        Schema::table('license', function (Blueprint $table) {
            // Unit value
            $table->decimal('unit_value_individual', 10, 2)->nullable()->after('unit_value');
            $table->decimal('unit_value_entity', 10, 2)->nullable()->after('unit_value_individual');
            $table->decimal('unit_value_federation', 10, 2)->nullable()->after('unit_value_entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license', function (Blueprint $table) {
            $table->dropColumn('unit_value_individual');
            $table->dropColumn('unit_value_entity');
            $table->dropColumn('unit_value_federation');
        });
    }
};
