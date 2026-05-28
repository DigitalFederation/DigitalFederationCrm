<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Seed the certification_sport pivot for generic coach certifications
     * (those with license_id IS NULL in the SPORT committee).
     * These certifications apply to all sports.
     */
    public function up(): void
    {
        $genericCoachCertIds = DB::table('certification')
            ->join('committee', 'certification.committee_id', '=', 'committee.id')
            ->join('professional_roles', 'certification.professional_role_id', '=', 'professional_roles.id')
            ->where('committee.code', 'SPORT')
            ->whereNull('certification.license_id')
            ->whereNull('certification.deleted_at')
            ->pluck('certification.id');

        $sportIds = DB::table('sports')->pluck('id');

        $rows = [];
        $now = now();

        foreach ($genericCoachCertIds as $certId) {
            foreach ($sportIds as $sportId) {
                $rows[] = [
                    'certification_id' => $certId,
                    'sport_id' => $sportId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (! empty($rows)) {
            DB::table('certification_sport')->insert($rows);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('certification_sport')->truncate();
    }
};
