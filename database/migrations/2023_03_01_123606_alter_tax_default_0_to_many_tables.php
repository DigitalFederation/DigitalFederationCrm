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
            $table->decimal('tax_value', 12, 2)->default(0)->nullable()->change();
            $table->decimal('tax_percentage', 5, 2)->default(0)->nullable()->change();
        });

        Schema::table('membership_plan', function (Blueprint $table) {
            $table->decimal('tax_value', 12, 2)->default(0)->nullable()->change();
            $table->decimal('tax_percentage', 5, 2)->default(0)->nullable()->change();
        });

        Schema::table('document_detail', function (Blueprint $table) {
            $table->decimal('tax_value', 12, 2)->default(0)->nullable()->change();
            $table->decimal('tax_percentage', 5, 2)->default(0)->nullable()->change();
        });

        Schema::table('document', function (Blueprint $table) {
            $table->decimal('tax_value', 12, 2)->default(0)->nullable()->change();
            $table->decimal('tax_percentage', 5, 2)->default(0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license ', function (Blueprint $table) {
            $table->decimal('tax_value', 12, 2)->change();
            $table->decimal('tax_percentage', 5, 2)->change();
        });

        Schema::table('membership_plan', function (Blueprint $table) {
            $table->decimal('tax_value', 12, 2)->change();
            $table->decimal('tax_percentage', 5, 2)->change();
        });

        Schema::table('document_detail', function (Blueprint $table) {
            $table->decimal('tax_value', 12, 2)->change();
            $table->decimal('tax_percentage', 5, 2)->change();
        });

        Schema::table('document', function (Blueprint $table) {
            $table->decimal('tax_value', 12, 2)->change();
            $table->decimal('tax_percentage', 5, 2)->change();
        });
    }
};
