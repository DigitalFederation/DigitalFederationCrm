<?php

use Domain\Licenses\States\PendingTechnicalDirectorApprovalLicenseAttributedState;
use Domain\Licenses\States\PendingValidationLicenseAttributedState;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fix CMAS DIVING licenses (international committee) that were incorrectly
     * placed in PendingTechnicalDirectorApprovalState due to a bug in
     * PurchaseLicenseAction that was fixed in commit 17307436 (2026-01-07).
     *
     * The bug used $license->is_international (which doesn't exist) instead of
     * $license->committee->isInternational(), causing all diving licenses to
     * go to TD approval regardless of their committee's international status.
     *
     * International DIVING licenses should go directly to PendingValidation,
     * only non-international DIVINGSERVICES should require TD approval.
     */
    public function up(): void
    {
        // Get the DIVING committee ID (international = true)
        $divingCommitteeId = DB::table('committee')
            ->where('code', 'DIVING')
            ->where('is_international', true)
            ->value('id');

        if (! $divingCommitteeId) {
            Log::warning('Migration: DIVING committee not found, skipping migration');

            return;
        }

        // Find license_attributed records that:
        // 1. Are in PendingTechnicalDirectorApprovalState (wrong state for international)
        // 2. Have a license that belongs to the DIVING committee (international)
        $affectedLicenses = DB::table('license_attributed as la')
            ->join('license as l', 'la.license_id', '=', 'l.id')
            ->where('l.committee_id', $divingCommitteeId)
            ->where('la.status_class', PendingTechnicalDirectorApprovalLicenseAttributedState::class)
            ->select('la.id', 'la.license_name', 'la.holder_name')
            ->get();

        if ($affectedLicenses->isEmpty()) {
            Log::info('Migration: No DIVING licenses found in wrong state');

            return;
        }

        Log::info('Migration: Found ' . $affectedLicenses->count() . ' DIVING licenses in wrong state', [
            'licenses' => $affectedLicenses->pluck('license_name', 'id')->toArray(),
        ]);

        // Update them to PendingValidationState (correct state for international licenses)
        $updated = DB::table('license_attributed')
            ->whereIn('id', $affectedLicenses->pluck('id'))
            ->update([
                'status_class' => PendingValidationLicenseAttributedState::class,
                'updated_at' => now(),
            ]);

        Log::info("Migration: Fixed {$updated} DIVING licenses - moved from PendingTechnicalDirectorApproval to PendingValidation");

        // Log activity for audit trail
        foreach ($affectedLicenses as $license) {
            DB::table('activity_log')->insert([
                'log_name' => 'license_attributed',
                'description' => 'License state corrected by migration: TD approval -> Pending validation (international license fix)',
                'subject_type' => 'Domain\\Licenses\\Models\\LicenseAttributed',
                'subject_id' => $license->id,
                'causer_type' => null,
                'causer_id' => null,
                'properties' => json_encode([
                    'from_state' => PendingTechnicalDirectorApprovalLicenseAttributedState::class,
                    'to_state' => PendingValidationLicenseAttributedState::class,
                    'reason' => 'Fix for international DIVING licenses incorrectly sent to TD approval',
                    'migration' => '2026_01_23_190000_fix_diving_licenses_wrong_state',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * Note: This doesn't restore the original wrong state, as that was a bug.
     * It's here for completeness but shouldn't be used.
     */
    public function down(): void
    {
        Log::warning('Migration rollback: Not reverting DIVING license state fix as original state was a bug');
    }
};
