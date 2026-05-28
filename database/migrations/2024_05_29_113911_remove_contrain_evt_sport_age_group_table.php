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
        // Drop the existing foreign key constraint
        Schema::table('evt_sport_age_groups', function (Blueprint $table) {
            $table->dropForeign(['sport_id']);
        });

        // Add the new foreign key constraint referencing the correct table
        Schema::table('evt_sport_age_groups', function (Blueprint $table) {
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the modified foreign key constraint
        Schema::table('evt_sport_age_groups', function (Blueprint $table) {
            $table->dropForeign(['sport_id']);
        });

        // Restore the original foreign key constraint
        Schema::table('evt_sport_age_groups', function (Blueprint $table) {
            $table->foreign('sport_id')->references('id')->on('evt_sports')->onDelete('cascade');
        });
    }
};
