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
        Schema::table('evt_events', function (Blueprint $table) {
            // Add boolean for public athlete list
            $table->boolean('public_athlete_list')->default(false);
            // Add boolean for public coach list
            $table->boolean('public_coach_list')->default(false);
            // Add boolean for public referee list
            $table->boolean('public_referee_list')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_events', function (Blueprint $table) {});
    }
};
