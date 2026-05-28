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
        Schema::table('payment_method', function (Blueprint $table) {
            $table->string('handler')->after('instructions')->nullable();  // Add the handler column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_method', function (Blueprint $table) {
            $table->dropColumn('handler');  // Drop the handler column
        });
    }
};
