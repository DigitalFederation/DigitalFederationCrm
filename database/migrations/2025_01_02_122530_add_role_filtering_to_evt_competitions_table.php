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
            $table->json('required_athlete_licenses')->nullable();
            $table->json('required_coach_certifications')->nullable();
            $table->json('required_referee_certifications')->nullable();

            $table->boolean('requires_athlete_adel')->default(false);
            $table->boolean('requires_coach_adel')->default(false);
            $table->boolean('requires_referee_adel')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_competitions', function (Blueprint $table) {
            //
        });
    }
};
