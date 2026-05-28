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
            $table->string('moloni_reference', 50)->nullable()->after('broadcast_information');
        });

        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->string('moloni_reference', 50)->nullable()->after('max_teams_per_athlete');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_events', function (Blueprint $table) {
            $table->dropColumn('moloni_reference');
        });

        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->dropColumn('moloni_reference');
        });
    }
};
