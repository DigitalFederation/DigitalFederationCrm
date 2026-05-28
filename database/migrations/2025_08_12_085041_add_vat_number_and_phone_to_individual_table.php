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
        Schema::table('individual', function (Blueprint $table) {
            $table->string('vat_number', 30)->nullable()->after('doc_ref_validation_date');
            $table->string('phone', 20)->nullable()->after('vat_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual', function (Blueprint $table) {
            $table->dropColumn(['vat_number', 'phone']);
        });
    }
};
