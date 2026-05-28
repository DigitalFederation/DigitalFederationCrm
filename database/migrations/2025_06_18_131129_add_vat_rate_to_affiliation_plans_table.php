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
            $table->integer('vat_rate')->default(23)->after('entity_fee')->comment('VAT rate percentage (0, 6, 13, 23)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliation_plans', function (Blueprint $table) {
            $table->dropColumn('vat_rate');
        });
    }
};
