<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Count records before fixing
        $incorrectRecords = DB::table('license_attributed')
            ->where('model_type', 'Domain\\Entities\\Models\\Entity')
            ->count();

        if ($incorrectRecords > 0) {
            Log::info('Starting fix for remaining entity morph types', [
                'incorrect_records_found' => $incorrectRecords,
            ]);

            // Fix the incorrect model_type values
            $updated = DB::table('license_attributed')
                ->where('model_type', 'Domain\\Entities\\Models\\Entity')
                ->update(['model_type' => 'entity']);

            Log::info('Fixed remaining entity morph types in license_attributed', [
                'records_updated' => $updated,
            ]);
        }

        // Also check and fix if there are any with single backslash
        $singleBackslashRecords = DB::table('license_attributed')
            ->where('model_type', 'Domain\Entities\Models\Entity')
            ->count();

        if ($singleBackslashRecords > 0) {
            $updated = DB::table('license_attributed')
                ->where('model_type', 'Domain\Entities\Models\Entity')
                ->update(['model_type' => 'entity']);

            Log::info('Fixed single backslash entity morph types in license_attributed', [
                'records_updated' => $updated,
            ]);
        }

        // Verify all records are now correct
        $remainingIncorrect = DB::table('license_attributed')
            ->where('model_type', 'LIKE', '%Entity%')
            ->where('model_type', '!=', 'entity')
            ->count();

        if ($remainingIncorrect > 0) {
            Log::warning('Some incorrect entity morph types may still remain', [
                'remaining_count' => $remainingIncorrect,
            ]);
        } else {
            Log::info('All entity morph types are now correctly set to "entity"');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't reverse this migration as we want to keep the correct morph types
        Log::info('Rollback of fix_remaining_entity_morph_types not implemented - morph types should remain as aliases');
    }
};
