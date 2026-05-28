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
            $table->boolean('is_validation_plan')->default(false)->after('vat_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliation_plans', function (Blueprint $table) {
            $table->dropColumn('is_validation_plan');
        });
    }
};
