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
        // Log the current state before migration
        $counts = [
            'entity_full' => DB::table('license_attributed')
                ->where('model_type', 'Domain\\Entities\\Models\\Entity')
                ->count(),
            'individual_full' => DB::table('license_attributed')
                ->where('model_type', 'Domain\\Individuals\\Models\\Individual')
                ->count(),
            'federation_full' => DB::table('license_attributed')
                ->where('model_type', 'Domain\\Federations\\Models\\Federation')
                ->count(),
        ];

        Log::info('Starting morph type fix migration', [
            'entity_records_to_fix' => $counts['entity_full'],
            'individual_records_to_fix' => $counts['individual_full'],
            'federation_records_to_fix' => $counts['federation_full'],
        ]);

        // Fix Entity morph types
        $entityUpdated = DB::table('license_attributed')
            ->where('model_type', 'Domain\\Entities\\Models\\Entity')
            ->update(['model_type' => 'entity']);

        // Fix Individual morph types
        $individualUpdated = DB::table('license_attributed')
            ->where('model_type', 'Domain\\Individuals\\Models\\Individual')
            ->update(['model_type' => 'individual']);

        // Fix Federation morph types
        $federationUpdated = DB::table('license_attributed')
            ->where('model_type', 'Domain\\Federations\\Models\\Federation')
            ->update(['model_type' => 'federation']);

        // Also fix requester_model_type column if it exists with full class names
        $requesterEntityUpdated = DB::table('license_attributed')
            ->where('requester_model_type', 'Domain\\Entities\\Models\\Entity')
            ->update(['requester_model_type' => 'entity']);

        $requesterIndividualUpdated = DB::table('license_attributed')
            ->where('requester_model_type', 'Domain\\Individuals\\Models\\Individual')
            ->update(['requester_model_type' => 'individual']);

        $requesterFederationUpdated = DB::table('license_attributed')
            ->where('requester_model_type', 'Domain\\Federations\\Models\\Federation')
            ->update(['requester_model_type' => 'federation']);

        Log::info('Morph type fix migration completed', [
            'entity_records_fixed' => $entityUpdated,
            'individual_records_fixed' => $individualUpdated,
            'federation_records_fixed' => $federationUpdated,
            'requester_entity_fixed' => $requesterEntityUpdated,
            'requester_individual_fixed' => $requesterIndividualUpdated,
            'requester_federation_fixed' => $requesterFederationUpdated,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This is a data fix migration.
        // Rolling back would require storing the original state,
        // which could be complex. In production, this should be
        // a one-way migration after proper backup.

        Log::warning('Morph type fix migration rollback called. This migration is not reversible. Original data format was not preserved.');
    }
};
