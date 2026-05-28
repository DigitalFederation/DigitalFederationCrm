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
            $table->string('moloni_reference', 50)->nullable()->after('tax_value');
        });

        Schema::table('certification', function (Blueprint $table) {
            $table->string('moloni_reference', 50)->nullable()->after('tax_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license', function (Blueprint $table) {
            $table->dropColumn('moloni_reference');
        });

        Schema::table('certification', function (Blueprint $table) {
            $table->dropColumn('moloni_reference');
        });
    }
};
