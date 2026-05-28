<?php

use App\Events\LicenseAttributedCreatedEvent;
use Domain\Licenses\Models\LicenseAttributed;
use Domain\Licenses\States\PendingLicenseAttributedState;
use Domain\Licenses\States\PendingValidationLicenseAttributedState;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fix international DIVING licenses that are incorrectly in PendingValidation state.
     * International licenses (CMAS DIVING, CMAS SCIENTIFIC) should skip admin validation
     * and go directly to payment pending state.
     *
     * This migration:
     * 1. Finds DIVING (international) licenses in PendingValidation state
     * 2. Moves them to PendingLicenseAttributedState (payment pending)
     * 3. Creates payment documents for them
     */
    public function up(): void
    {
        // Get the DIVING committee ID (international = true)
        $divingCommittee = DB::table('committee')
            ->where('code', 'DIVING')
            ->where('is_international', true)
            ->first();

        if (! $divingCommittee) {
            Log::warning('Migration: DIVING committee not found or not international, skipping');

            return;
        }

        // Find DIVING (international) licenses that are incorrectly in PendingValidation
        $affectedLicenses = DB::table('license_attributed as la')
            ->join('license as l', 'la.license_id', '=', 'l.id')
            ->where('l.committee_id', $divingCommittee->id)
            ->where('la.status_class', PendingValidationLicenseAttributedState::class)
            ->whereNull('la.deleted_at')
            ->select('la.id', 'la.license_name', 'la.holder_name', 'la.total_value')
            ->get();

        if ($affectedLicenses->isEmpty()) {
            Log::info('Migration: No international DIVING licenses found in PendingValidation state');

            return;
        }

        Log::info('Migration: Found ' . $affectedLicenses->count() . ' international DIVING licenses in PendingValidation state', [
            'licenses' => $affectedLicenses->pluck('license_name', 'id')->toArray(),
        ]);

        $successCount = 0;
        $errorCount = 0;

        foreach ($affectedLicenses as $licenseData) {
            try {
                DB::beginTransaction();

                // Update state to PendingLicenseAttributedState (payment pending)
                DB::table('license_attributed')
                    ->where('id', $licenseData->id)
                    ->update([
                        'status_class' => PendingLicenseAttributedState::class,
                        'updated_at' => now(),
                    ]);

                // Load the full model to create payment document
                $licenseAttributed = LicenseAttributed::withoutGlobalScopes()
                    ->with('license')
                    ->find($licenseData->id);

                if ($licenseAttributed && $licenseAttributed->total_value > 0) {
                    // Fire event to create payment document
                    event(new LicenseAttributedCreatedEvent([$licenseAttributed], true));

                    Log::info('Migration: Created payment document for international DIVING license', [
                        'license_attributed_id' => $licenseData->id,
                        'license_name' => $licenseData->license_name,
                        'total_value' => $licenseData->total_value,
                    ]);
                }

                // Log activity for audit trail
                DB::table('activity_log')->insert([
                    'log_name' => 'license_attributed',
                    'description' => 'License state corrected by migration: PendingValidation -> Pending (international license skip admin validation)',
                    'subject_type' => 'Domain\\Licenses\\Models\\LicenseAttributed',
                    'subject_id' => $licenseData->id,
                    'causer_type' => null,
                    'causer_id' => null,
                    'properties' => json_encode([
                        'from_state' => PendingValidationLicenseAttributedState::class,
                        'to_state' => PendingLicenseAttributedState::class,
                        'reason' => 'International DIVING licenses should skip admin validation and go directly to payment',
                        'migration' => '2026_01_23_190631_fix_international_diving_licenses_skip_admin_validation',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::commit();
                $successCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Migration: Failed to fix international DIVING license', [
                    'license_attributed_id' => $licenseData->id,
                    'error' => $e->getMessage(),
                ]);
                $errorCount++;
            }
        }

        Log::info('Migration: Completed fixing international DIVING licenses', [
            'success' => $successCount,
            'errors' => $errorCount,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Log::warning('Migration rollback: Not reverting international DIVING license fix as original state was incorrect');
    }
};
