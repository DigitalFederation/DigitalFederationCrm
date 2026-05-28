<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->json('required_athlete_documents')->nullable()->after('requires_official_adel');
            $table->json('required_coach_documents')->nullable()->after('required_athlete_documents');
            $table->json('required_referee_documents')->nullable()->after('required_coach_documents');
            $table->json('required_official_documents')->nullable()->after('required_referee_documents');
        });

        // Migrate existing boolean values to JSON arrays
        DB::table('evt_competitions')
            ->where('requires_athlete_adel', true)
            ->update(['required_athlete_documents' => json_encode(['ADELCertificate'])]);

        DB::table('evt_competitions')
            ->where('requires_coach_adel', true)
            ->update(['required_coach_documents' => json_encode(['ADELCertificate'])]);

        DB::table('evt_competitions')
            ->where('requires_referee_adel', true)
            ->update(['required_referee_documents' => json_encode(['ADELCertificate'])]);

        DB::table('evt_competitions')
            ->where('requires_official_adel', true)
            ->update(['required_official_documents' => json_encode(['ADELCertificate'])]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->dropColumn([
                'required_athlete_documents',
                'required_coach_documents',
                'required_referee_documents',
                'required_official_documents',
            ]);
        });
    }
};
