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
        Schema::table('document', function (Blueprint $table) {
            $table->unsignedInteger('invoice_number')->nullable();
            $table->year('invoice_year')->nullable();
            $table->unique(['invoice_number', 'invoice_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document', function (Blueprint $table) {
            $table->dropColumn('invoice_number');
            $table->dropColumn('invoice_year');
        });
    }
};
