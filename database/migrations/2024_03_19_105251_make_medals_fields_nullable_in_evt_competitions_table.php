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
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->integer('medals_gold')->nullable()->change();
            $table->integer('medals_silver')->nullable()->change();
            $table->integer('medals_bronze')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_competitions', function (Blueprint $table) {
            // Revert back to non-nullable and default value of 0
            $table->integer('medals_gold')->default(0)->change();
            $table->integer('medals_silver')->default(0)->change();
            $table->integer('medals_bronze')->default(0)->change();
        });
    }
};
