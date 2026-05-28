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
            $table->dropColumn('price');
            $table->decimal('unit_value', 12, 2)->nullable();
            $table->decimal('tax_value', 12, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license', function (Blueprint $table) {
            $table->dropColumn('unit_value');
            $table->dropColumn('tax_value');
            $table->dropColumn('tax_percentage');
            $table->decimal('price', 12, 2)->nullable();
        });
    }
};
