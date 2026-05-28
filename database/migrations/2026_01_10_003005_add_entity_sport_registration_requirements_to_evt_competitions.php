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
            $table->boolean('requires_athlete_entity_sport_registration')->default(true)
                ->after('requires_local_federation_affiliation')
                ->comment('Requires athlete to be registered for the competition sport in the enrolling entity');

            $table->boolean('requires_coach_entity_sport_registration')->default(true)
                ->after('requires_athlete_entity_sport_registration')
                ->comment('Requires coach to be registered for the competition sport in the enrolling entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->dropColumn([
                'requires_athlete_entity_sport_registration',
                'requires_coach_entity_sport_registration',
            ]);
        });
    }
};
