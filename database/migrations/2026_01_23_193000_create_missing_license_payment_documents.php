<?php

use App\Events\LicenseAttributedCreatedEvent;
use Domain\Licenses\Models\LicenseAttributed;
use Domain\Licenses\States\PendingLicenseAttributedState;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Create missing payment documents for licenses that are in PendingLicenseAttributedState
     * (meaning they need payment) but don't have any associated payment documents.
     *
     * This fixes licenses that were transitioned to Pending state without the proper
     * event being fired to create the payment document.
     */
    public function up(): void
    {
        // Find license_attributed records that:
        // 1. Are in PendingLicenseAttributedState (waiting for payment)
        // 2. Have a total_value > 0 (not free)
        // 3. Don't have any document_detail records
        $licensesWithoutDocuments = DB::table('license_attributed as la')
            ->leftJoin('document_detail as dd', function ($join) {
                $join->on('dd.owner_id', '=', 'la.id')
                    ->where('dd.owner_type', '=', LicenseAttributed::class);
            })
            ->where('la.status_class', PendingLicenseAttributedState::class)
            ->where('la.total_value', '>', 0)
            ->whereNull('dd.id')
            ->whereNull('la.deleted_at')
            ->select('la.id', 'la.license_name', 'la.holder_name', 'la.total_value')
            ->get();

        if ($licensesWithoutDocuments->isEmpty()) {
            Log::info('Migration: No licenses found in Pending state without payment documents');

            return;
        }

        Log::info('Migration: Found ' . $licensesWithoutDocuments->count() . ' licenses in Pending state without payment documents', [
            'licenses' => $licensesWithoutDocuments->pluck('license_name', 'id')->toArray(),
        ]);

        $successCount = 0;
        $errorCount = 0;

        foreach ($licensesWithoutDocuments as $licenseData) {
            try {
                // Load the full model with relationships
                $licenseAttributed = LicenseAttributed::withoutGlobalScopes()
                    ->with('license')
                    ->find($licenseData->id);

                if (! $licenseAttributed) {
                    Log::warning('Migration: Could not find license attributed', ['id' => $licenseData->id]);
                    $errorCount++;

                    continue;
                }

                // Fire the event to create the payment document
                event(new LicenseAttributedCreatedEvent([$licenseAttributed], true));

                Log::info('Migration: Created payment document for license', [
                    'license_attributed_id' => $licenseData->id,
                    'license_name' => $licenseData->license_name,
                    'holder_name' => $licenseData->holder_name,
                    'total_value' => $licenseData->total_value,
                ]);

                $successCount++;

                // Log activity for audit trail
                DB::table('activity_log')->insert([
                    'log_name' => 'license_attributed',
                    'description' => 'Payment document created by migration for license missing document',
                    'subject_type' => LicenseAttributed::class,
                    'subject_id' => $licenseData->id,
                    'causer_type' => null,
                    'causer_id' => null,
                    'properties' => json_encode([
                        'reason' => 'License was in Pending state without payment document',
                        'migration' => '2026_01_23_193000_create_missing_license_payment_documents',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            } catch (\Exception $e) {
                Log::error('Migration: Failed to create payment document for license', [
                    'license_attributed_id' => $licenseData->id,
                    'error' => $e->getMessage(),
                ]);
                $errorCount++;
            }
        }

        Log::info('Migration: Completed creating missing payment documents', [
            'success' => $successCount,
            'errors' => $errorCount,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Log::warning('Migration rollback: Cannot reverse payment document creation automatically');
    }
};
