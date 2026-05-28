<?php

use Domain\Licenses\States\ActiveLicenseAttributedState;
use Domain\Licenses\States\PendingValidationLicenseAttributedState;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up(): void
    {
        // Get the DIVINGSERVICES committee ID
        $committeeId = DB::table('committee')
            ->where('code', 'DIVINGSERVICES')
            ->value('id');

        if (! $committeeId) {
            Log::warning('Migration: DIVINGSERVICES committee not found, skipping');

            return;
        }

        // Fix the license flag: EM should require admin validation like the other DIVINGSERVICES licenses
        $updated = DB::table('license')
            ->where('license_code', 'EM')
            ->where('committee_id', $committeeId)
            ->update(['requires_admin_validation' => true]);

        if ($updated) {
            Log::info('Migration: Set requires_admin_validation=1 for Escola de Mergulho (EM) license');
        } else {
            Log::warning('Migration: EM license not found under DIVINGSERVICES committee');

            return;
        }

        // Fix existing bad data: EM licenses that went directly to Active without federation validation.
        $licenseId = DB::table('license')
            ->where('license_code', 'EM')
            ->where('committee_id', $committeeId)
            ->value('id');

        $affectedRecords = DB::table('license_attributed')
            ->where('license_id', $licenseId)
            ->where('status_class', ActiveLicenseAttributedState::class)
            ->whereNull('validated_by')
            ->select('id', 'license_name', 'holder_name')
            ->get();

        if ($affectedRecords->isEmpty()) {
            Log::info('Migration: No incorrectly activated EM licenses found');

            return;
        }

        DB::table('license_attributed')
            ->whereIn('id', $affectedRecords->pluck('id'))
            ->update([
                'status_class' => PendingValidationLicenseAttributedState::class,
                'activated_at' => null,
                'updated_at' => now(),
            ]);

        Log::info("Migration: Reset {$affectedRecords->count()} EM license(s) to PendingValidation");

        // Audit trail
        foreach ($affectedRecords as $record) {
            DB::table('activity_log')->insert([
                'log_name' => 'license_attributed',
                'description' => 'License state corrected by migration: EM license was activated without federation validation',
                'subject_type' => 'Domain\\Licenses\\Models\\LicenseAttributed',
                'subject_id' => $record->id,
                'properties' => json_encode([
                    'from_state' => ActiveLicenseAttributedState::class,
                    'to_state' => PendingValidationLicenseAttributedState::class,
                    'reason' => 'Escola de Mergulho (EM) had requires_admin_validation=0, bypassing TD approval and federation validation',
                    'migration' => '2026_02_28_000043_fix_escola_mergulho_requires_admin_validation',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $committeeId = DB::table('committee')
            ->where('code', 'DIVINGSERVICES')
            ->value('id');

        if (! $committeeId) {
            return;
        }

        DB::table('license')
            ->where('license_code', 'EM')
            ->where('committee_id', $committeeId)
            ->update(['requires_admin_validation' => false]);

        Log::warning('Migration rollback: Reverted requires_admin_validation for EM license. Note: affected license_attributed records were NOT reverted to Active.');
    }
};
