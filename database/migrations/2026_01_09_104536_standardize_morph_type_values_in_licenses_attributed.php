<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Standardize morph type values to use morph aliases instead of full class names.
     *
     * The morph map is defined in AppServiceProvider:
     * - 'entity' => \Domain\Entities\Models\Entity::class
     * - 'individual' => \Domain\Individuals\Models\Individual::class
     * - 'federation' => \Domain\Federations\Models\Federation::class
     *
     * Some records were created with full class names instead of morph aliases.
     * This migration converts them to the correct format.
     */
    public function up(): void
    {
        $mappings = [
            'Domain\\Entities\\Models\\Entity' => 'entity',
            'Domain\\Individuals\\Models\\Individual' => 'individual',
            'Domain\\Federations\\Models\\Federation' => 'federation',
        ];

        foreach ($mappings as $className => $morphAlias) {
            $affected = DB::table('license_attributed')
                ->where('model_type', $className)
                ->update(['model_type' => $morphAlias]);

            if ($affected > 0) {
                Log::info("Standardized {$affected} license_attributed records from '{$className}' to '{$morphAlias}'");
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * Note: This converts back to full class names, but this is not recommended
     * as the morph alias format is the correct Laravel convention.
     */
    public function down(): void
    {
        $mappings = [
            'entity' => 'Domain\\Entities\\Models\\Entity',
            'individual' => 'Domain\\Individuals\\Models\\Individual',
            'federation' => 'Domain\\Federations\\Models\\Federation',
        ];

        foreach ($mappings as $morphAlias => $className) {
            DB::table('license_attributed')
                ->where('model_type', $morphAlias)
                ->update(['model_type' => $className]);
        }
    }
};
