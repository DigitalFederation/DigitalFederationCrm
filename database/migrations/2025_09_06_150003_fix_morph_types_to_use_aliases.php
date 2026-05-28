<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Map of full class names to morph aliases
        $morphMap = [
            'Domain\Entities\Models\Entity' => 'entity',
            'Domain\\Entities\\Models\\Entity' => 'entity',
            'Domain\Individuals\Models\Individual' => 'individual',
            'Domain\\Individuals\\Models\\Individual' => 'individual',
            'Domain\Federations\Models\Federation' => 'federation',
            'Domain\\Federations\\Models\\Federation' => 'federation',
        ];

        // Fix document table owner_type
        foreach ($morphMap as $className => $alias) {
            DB::table('document')
                ->where('owner_type', $className)
                ->update(['owner_type' => $alias]);
        }

        // Fix document_detail table owner_type
        // Note: We should NOT change LicenseAttributed, MemberSubscription, Enrollment etc.
        // as they don't have morph aliases defined
        foreach ($morphMap as $className => $alias) {
            DB::table('document_detail')
                ->where('owner_type', $className)
                ->update(['owner_type' => $alias]);
        }

        // Fix license_attributed table model_type
        foreach ($morphMap as $className => $alias) {
            DB::table('license_attributed')
                ->where('model_type', $className)
                ->update(['model_type' => $alias]);
        }

        // Fix license_attributed table requester_model_type
        foreach ($morphMap as $className => $alias) {
            DB::table('license_attributed')
                ->where('requester_model_type', $className)
                ->update(['requester_model_type' => $alias]);
        }

        // Fix other polymorphic tables that might have these issues

        // Fix attachments table owner_type
        if (Schema::hasTable('attachments')) {
            foreach ($morphMap as $className => $alias) {
                DB::table('attachments')
                    ->where('owner_type', $className)
                    ->update(['owner_type' => $alias]);
            }
        }

        // Fix official_documents table owner_type
        if (Schema::hasTable('official_documents')) {
            foreach ($morphMap as $className => $alias) {
                DB::table('official_documents')
                    ->where('owner_type', $className)
                    ->update(['owner_type' => $alias]);
            }
        }

        // Fix diving_locations table owner_type
        if (Schema::hasTable('diving_locations')) {
            foreach ($morphMap as $className => $alias) {
                DB::table('diving_locations')
                    ->where('owner_type', $className)
                    ->update(['owner_type' => $alias]);
            }
        }

        // Log the changes
        $documentCount = DB::table('document')
            ->whereIn('owner_type', ['entity', 'individual', 'federation'])
            ->count();

        $licenseCount = DB::table('license_attributed')
            ->whereIn('model_type', ['entity', 'individual', 'federation'])
            ->count();

        \Log::info('Morph type migration completed', [
            'documents_with_aliases' => $documentCount,
            'licenses_with_aliases' => $licenseCount,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse map - from aliases back to full class names
        $reverseMap = [
            'entity' => 'Domain\\Entities\\Models\\Entity',
            'individual' => 'Domain\\Individuals\\Models\\Individual',
            'federation' => 'Domain\\Federations\\Models\\Federation',
        ];

        // Revert document table owner_type
        foreach ($reverseMap as $alias => $className) {
            DB::table('document')
                ->where('owner_type', $alias)
                ->update(['owner_type' => $className]);
        }

        // Revert document_detail table owner_type
        foreach ($reverseMap as $alias => $className) {
            DB::table('document_detail')
                ->where('owner_type', $alias)
                ->update(['owner_type' => $className]);
        }

        // Revert license_attributed table model_type
        foreach ($reverseMap as $alias => $className) {
            DB::table('license_attributed')
                ->where('model_type', $alias)
                ->update(['model_type' => $className]);
        }

        // Revert license_attributed table requester_model_type
        foreach ($reverseMap as $alias => $className) {
            DB::table('license_attributed')
                ->where('requester_model_type', $alias)
                ->update(['requester_model_type' => $className]);
        }

        // Revert other tables
        if (Schema::hasTable('attachments')) {
            foreach ($reverseMap as $alias => $className) {
                DB::table('attachments')
                    ->where('owner_type', $alias)
                    ->update(['owner_type' => $className]);
            }
        }

        if (Schema::hasTable('official_documents')) {
            foreach ($reverseMap as $alias => $className) {
                DB::table('official_documents')
                    ->where('owner_type', $alias)
                    ->update(['owner_type' => $className]);
            }
        }

        if (Schema::hasTable('diving_locations')) {
            foreach ($reverseMap as $alias => $className) {
                DB::table('diving_locations')
                    ->where('owner_type', $alias)
                    ->update(['owner_type' => $className]);
            }
        }
    }
};
