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
        Schema::table('affiliation_plans', function (Blueprint $table) {
            $table->string('moloni_reference', 50)->nullable()->after('entity_fee');
        });

        Schema::table('insurance_plans', function (Blueprint $table) {
            $table->string('moloni_reference', 50)->nullable()->after('entity_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliation_plans', function (Blueprint $table) {
            $table->dropColumn('moloni_reference');
        });

        Schema::table('insurance_plans', function (Blueprint $table) {
            $table->dropColumn('moloni_reference');
        });
    }
};
