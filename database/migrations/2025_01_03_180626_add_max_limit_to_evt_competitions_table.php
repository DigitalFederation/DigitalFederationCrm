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
            $table->integer('max_disciplines_per_athlete')->nullable()->default(null);
            $table->integer('max_relays_per_athlete')->nullable()->default(null);
            $table->integer('max_teams_per_athlete')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->dropColumn([
                'max_disciplines_per_athlete',
                'max_relays_per_athlete',
                'max_teams_per_athlete',
            ]);
        });
    }

};
