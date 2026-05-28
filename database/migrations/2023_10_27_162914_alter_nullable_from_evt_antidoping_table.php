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
        Schema::table('evt_antidoping', function (Blueprint $table) {
            $table->integer('num_controls_planned')->nullable()->change();
            $table->integer('number_of_controls')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_antidoping', function (Blueprint $table) {
            $table->integer('num_controls_planned')->change();
            $table->integer('number_of_controls')->change();
        });
    }
};
