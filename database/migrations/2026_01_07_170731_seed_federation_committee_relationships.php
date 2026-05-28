<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Seed federation_committee relationships based on:
     * - Main federation: all 4 committees
     * - Local Sport federations: SPORT only
     * - International federation: DIVING, SCIENTIFIC (international only)
     * - Other federations: inferred from their licenses
     */
    public function up(): void
    {
        $internationalFederationName = config('branding.international.name', 'International Federation');

        // Get committee IDs
        $sportId = DB::table('committee')->where('code', 'SPORT')->value('id');
        $divingServicesId = DB::table('committee')->where('code', 'DIVINGSERVICES')->value('id');
        $divingId = DB::table('committee')->where('code', 'DIVING')->value('id');
        $scientificId = DB::table('committee')->where('code', 'SCIENTIFIC')->value('id');

        $allCommittees = array_filter([$sportId, $divingServicesId, $divingId, $scientificId]);
        $now = now();

        // 1. Main federation - all committees
        $mainFederation = DB::table('federation')
            ->where('is_default_federation', true)
            ->first();

        if ($mainFederation) {
            foreach ($allCommittees as $committeeId) {
                DB::table('federation_committee')->insertOrIgnore([
                    'federation_id' => $mainFederation->id,
                    'committee_id' => $committeeId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // 2. International federation - DIVING, SCIENTIFIC only
        $internationalFederation = DB::table('federation')
            ->where('name', 'LIKE', "%{$internationalFederationName}%")
            ->first();

        if ($internationalFederation) {
            foreach ([$divingId, $scientificId] as $committeeId) {
                if ($committeeId) {
                    DB::table('federation_committee')->insertOrIgnore([
                        'federation_id' => $internationalFederation->id,
                        'committee_id' => $committeeId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        // 3. Local federations - infer from their licenses
        $localFederations = DB::table('federation')
            ->where('is_local', true)
            ->where('is_default_federation', false)
            ->whereNotNull('parent_id')
            ->get();

        foreach ($localFederations as $federation) {
            // Get committee IDs from federation's licenses
            $licenseCommitteeIds = DB::table('federation_licenses')
                ->join('license', 'federation_licenses.license_id', '=', 'license.id')
                ->where('federation_licenses.federation_id', $federation->id)
                ->whereNotNull('license.committee_id')
                ->distinct()
                ->pluck('license.committee_id');

            // If federation has licenses, use those committees
            // Otherwise, default to SPORT for local federations
            $committeeIdsToAssign = $licenseCommitteeIds->isNotEmpty()
                ? $licenseCommitteeIds->toArray()
                : [$sportId];

            foreach ($committeeIdsToAssign as $committeeId) {
                if ($committeeId) {
                    DB::table('federation_committee')->insertOrIgnore([
                        'federation_id' => $federation->id,
                        'committee_id' => $committeeId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        // Log summary
        $count = DB::table('federation_committee')->count();
        \Log::info("Seeded {$count} federation_committee relationships");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('federation_committee')->truncate();
    }
};
