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
        // Only run if tables exist
        if (! Schema::hasTable('official_documents') || ! Schema::hasTable('licenses')) {
            return;
        }

        // Map old document types to new ones if any exist
        $mappings = [
            'BusinessLicense' => 'EntityStatutes',
            'TaxRegistration' => 'EntityLegalPersonality',
            'LegalRepresentativeDocument' => 'TechnicalDirectorIdentification',
            'EntityInsurance' => 'EntityAccidentInsurance',
            'BankAccountDocument' => 'EntityLegalPersonality',
            'FacilityLicense' => 'EntityStatutes',
            'SafetyCompliance' => 'EntityAccidentInsurance',
        ];

        // Update official_documents table
        foreach ($mappings as $oldType => $newType) {
            DB::table('official_documents')
                ->where('type', $oldType)
                ->update(['type' => $newType]);
        }

        // Update licenses table - required_document_types JSON column
        $licenses = DB::table('licenses')
            ->whereNotNull('required_document_types')
            ->get();

        foreach ($licenses as $license) {
            $requiredTypes = json_decode($license->required_document_types, true);
            if (! is_array($requiredTypes)) {
                continue;
            }

            $updated = false;
            foreach ($requiredTypes as &$type) {
                if (isset($mappings[$type])) {
                    $type = $mappings[$type];
                    $updated = true;
                }
            }

            if ($updated) {
                DB::table('licenses')
                    ->where('id', $license->id)
                    ->update(['required_document_types' => json_encode($requiredTypes)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only run if tables exist
        if (! Schema::hasTable('official_documents') || ! Schema::hasTable('licenses')) {
            return;
        }

        // Reverse mappings
        $mappings = [
            'EntityStatutes' => 'BusinessLicense',
            'EntityLegalPersonality' => 'TaxRegistration',
            'TechnicalDirectorIdentification' => 'LegalRepresentativeDocument',
            'EntityAccidentInsurance' => 'EntityInsurance',
            'TechnicalDirectorTraining' => 'LegalRepresentativeDocument',
            'TechnicalDirectorGasHandling' => 'LegalRepresentativeDocument',
        ];

        // Update official_documents table
        foreach ($mappings as $newType => $oldType) {
            DB::table('official_documents')
                ->where('type', $newType)
                ->update(['type' => $oldType]);
        }

        // Update licenses table - required_document_types JSON column
        $licenses = DB::table('licenses')
            ->whereNotNull('required_document_types')
            ->get();

        foreach ($licenses as $license) {
            $requiredTypes = json_decode($license->required_document_types, true);
            if (! is_array($requiredTypes)) {
                continue;
            }

            $updated = false;
            foreach ($requiredTypes as &$type) {
                if (isset($mappings[$type])) {
                    $type = $mappings[$type];
                    $updated = true;
                }
            }

            if ($updated) {
                DB::table('licenses')
                    ->where('id', $license->id)
                    ->update(['required_document_types' => json_encode($requiredTypes)]);
            }
        }
    }
};
