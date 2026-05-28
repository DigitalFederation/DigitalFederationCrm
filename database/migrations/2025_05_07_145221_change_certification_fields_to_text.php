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
        Schema::table('certification', function (Blueprint $table) {
            $table->string('minimum_age')->nullable()->change();
            $table->string('confined_water_sessions')->nullable()->change();
            $table->string('open_water_sessions')->nullable()->change();
            $table->string('theoretical_sessions')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification', function (Blueprint $table) {
            $table->integer('minimum_age')->nullable()->change();
            $table->integer('confined_water_sessions')->nullable()->change();
            $table->integer('open_water_sessions')->nullable()->change();
            $table->integer('theoretical_sessions')->nullable()->change();
        });
    }
};
