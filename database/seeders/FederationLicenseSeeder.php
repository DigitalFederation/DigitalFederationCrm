<?php

namespace Database\Seeders;

use Domain\Federations\Models\Federation;
use Domain\Licenses\Models\License;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FederationLicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder populates the federation_licenses table with initial data.
     * It assigns licenses to federations based on their committee memberships.
     */
    public function run(): void
    {
        $this->command->info('Starting Federation License Seeder...');

        // Begin transaction for data integrity
        DB::beginTransaction();

        try {
            // 1. Assign all licenses to the main/default federation
            $this->assignLicensesToMainFederation();

            // 2. Assign committee-specific licenses to federations
            $this->assignCommitteeSpecificLicenses();

            // 3. Handle special cases
            $this->handleSpecialCases();

            DB::commit();
            $this->command->info('Federation License Seeder completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Federation License Seeder failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Assign all available licenses to the main/default federation
     */
    private function assignLicensesToMainFederation(): void
    {
        $mainFederation = Federation::where('is_default_federation', true)->first();

        if (! $mainFederation) {
            $this->command->warn('No default federation found. Skipping main federation assignment.');

            return;
        }

        $allLicenseIds = License::pluck('id')->toArray();

        if (empty($allLicenseIds)) {
            $this->command->warn('No licenses found in the database.');

            return;
        }

        // Sync all licenses to the main federation
        $mainFederation->licenses()->sync($allLicenseIds);

        $this->command->info('Assigned ' . count($allLicenseIds) . " licenses to main federation: {$mainFederation->name}");
    }

    /**
     * Assign committee-specific licenses to federations based on their memberships
     */
    private function assignCommitteeSpecificLicenses(): void
    {
        // Get committee codes to process
        $committees = ['sport', 'diving', 'scientific', 'technical'];

        foreach ($committees as $committee) {
            $this->command->info("Processing {$committee} committee licenses...");

            // Find federations that have memberships with this committee
            $federations = Federation::whereHas('memberships.plans', function ($query) use ($committee) {
                $query->whereHas('committee', function ($q) use ($committee) {
                    $q->where('code', $committee);
                });
            })->get();

            if ($federations->isEmpty()) {
                $this->command->info("No federations found with {$committee} committee memberships.");

                continue;
            }

            // Get licenses for this committee
            $licenseIds = License::hasCommitteeCode($committee)->pluck('id')->toArray();

            if (empty($licenseIds)) {
                $this->command->info("No licenses found for {$committee} committee.");

                continue;
            }

            // Assign licenses to each federation
            foreach ($federations as $federation) {
                // Use syncWithoutDetaching to add without removing existing
                $federation->licenses()->syncWithoutDetaching($licenseIds);
                $this->command->info("  - Assigned {$committee} licenses to: {$federation->name}");
            }
        }
    }

    /**
     * Handle special cases for specific federations or license types
     */
    private function handleSpecialCases(): void
    {
        // Example: Assign international licenses to all international federations
        $internationalFederations = Federation::where('is_international', true)->get();

        if ($internationalFederations->isNotEmpty()) {
            $internationalLicenseIds = License::whereHas('committee', fn ($q) => $q->where('is_international', true))->pluck('id')->toArray();

            foreach ($internationalFederations as $federation) {
                $federation->licenses()->syncWithoutDetaching($internationalLicenseIds);
                $this->command->info("Assigned international licenses to: {$federation->name}");
            }
        }

        // Example: Ensure local federations have basic entity licenses
        $localFederations = Federation::where('is_local', true)->get();

        if ($localFederations->isNotEmpty()) {
            // Get basic entity licenses (you might need to adjust this query)
            $basicEntityLicenseIds = License::hasLicenseType('entity')
                ->where('requester_model', \Domain\Entities\Models\Entity::class)
                ->pluck('id')
                ->toArray();

            foreach ($localFederations as $federation) {
                $federation->licenses()->syncWithoutDetaching($basicEntityLicenseIds);
                $this->command->info("Assigned basic entity licenses to local federation: {$federation->name}");
            }
        }
    }
}
