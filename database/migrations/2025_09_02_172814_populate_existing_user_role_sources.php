<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Populate existing user roles into the provenance tracking table
        // We'll mark all existing roles as 'manual' since we don't know their true source

        $userRoles = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->get();

        foreach ($userRoles as $userRole) {
            // Check if this role might be from a license
            // by looking for active licenses on entities this user administers
            $isLikelyFromLicense = false;
            $licenseId = null;

            // Get user's entities
            $userEntities = DB::table('entity_user')
                ->where('user_id', $userRole->model_id)
                ->pluck('entity_id');

            if ($userEntities->isNotEmpty()) {
                // Check if any entity has active licenses that would grant this role
                $licensesWithRole = DB::table('license_attributed as la')
                    ->join('license_roles as lr', 'la.license_id', '=', 'lr.license_id')
                    ->where('lr.role_id', $userRole->role_id)
                    ->where('la.status_class', 'Domain\\Licenses\\States\\ActiveLicenseAttributedState')
                    ->where('la.model_type', 'entity')
                    ->whereIn('la.model_id', $userEntities)
                    ->first();

                if ($licensesWithRole) {
                    $isLikelyFromLicense = true;
                    $licenseId = $licensesWithRole->id;
                }
            }

            // Insert into user_role_sources
            DB::table('user_role_sources')->insertOrIgnore([
                'user_id' => $userRole->model_id,
                'role_id' => $userRole->role_id,
                'source_type' => $isLikelyFromLicense ? 'license' : 'manual',
                'source_id' => $licenseId,
                'assigned_at' => now(),
                'assigned_by' => null, // We don't know who originally assigned it
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear the user_role_sources table
        DB::table('user_role_sources')->truncate();
    }
};
