<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Old role names to be consolidated
     */
    private array $oldRoleNames = [
        'individual-judge',
        'individual-referee',
        'view-individual-judge',
        'view-individual-referee',
    ];

    /**
     * Run the migrations.
     *
     * Consolidates judge and referee roles into technical official roles.
     * This aligns with the previous migration (2026_01_11_224200) that merged
     * REFEREE, JUDGE, and REFEREEJUDGE professional role types into TECHNICAL_OFFICIAL.
     */
    public function up(): void
    {
        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            // Step 1: Create new consolidated roles with their permissions
            $this->createTechnicalOfficialRole();
            $this->createViewTechnicalOfficialRole();

            // Step 2: Migrate users from old roles to new roles
            $this->migrateUsersToNewRoles();

            // Step 3: Update license_roles pivot table
            $this->updateLicenseRoles();

            // Step 4: Update certification_roles pivot table
            $this->updateCertificationRoles();

            // Step 5: Delete old roles
            $this->deleteOldRoles();

            Log::info('Successfully consolidated judge/referee roles into technical official roles');
        });

        // Clear permission cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Create the individual-technical-official role with merged permissions
     */
    private function createTechnicalOfficialRole(): void
    {
        $role = Role::firstOrCreate(
            ['name' => 'individual-technical-official', 'guard_name' => 'web']
        );

        // Merged permissions from both judge and referee roles
        $permissions = [
            'access judge menu',
            'access referee menu',
            'access events',
            'access sport individual licenses attributed',
            'access referee-judge official documents',
            'access files area menu',
        ];

        // Ensure permissions exist and sync them
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        $role->syncPermissions($permissions);

        Log::info('Created individual-technical-official role with merged permissions');
    }

    /**
     * Create the view-individual-technical-official role with merged permissions
     */
    private function createViewTechnicalOfficialRole(): void
    {
        $role = Role::firstOrCreate(
            ['name' => 'view-individual-technical-official', 'guard_name' => 'web']
        );

        // Merged permissions from both view-judge and view-referee roles
        $permissions = [
            'access judge menu',
            'access referee menu',
            'access sport menu',
        ];

        // Ensure permissions exist and sync them
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        $role->syncPermissions($permissions);

        Log::info('Created view-individual-technical-official role with merged permissions');
    }

    /**
     * Migrate users from old roles to new consolidated roles
     */
    private function migrateUsersToNewRoles(): void
    {
        $technicalOfficialRole = Role::where('name', 'individual-technical-official')->first();
        $viewTechnicalOfficialRole = Role::where('name', 'view-individual-technical-official')->first();

        // Get old role IDs
        $judgeRole = Role::where('name', 'individual-judge')->first();
        $refereeRole = Role::where('name', 'individual-referee')->first();
        $viewJudgeRole = Role::where('name', 'view-individual-judge')->first();
        $viewRefereeRole = Role::where('name', 'view-individual-referee')->first();

        // Migrate users with individual-judge or individual-referee to individual-technical-official
        $userIdsToMigrate = collect();

        if ($judgeRole) {
            $judgeUserIds = DB::table('model_has_roles')
                ->where('role_id', $judgeRole->id)
                ->where('model_type', 'App\\Models\\User')
                ->pluck('model_id');
            $userIdsToMigrate = $userIdsToMigrate->merge($judgeUserIds);
        }

        if ($refereeRole) {
            $refereeUserIds = DB::table('model_has_roles')
                ->where('role_id', $refereeRole->id)
                ->where('model_type', 'App\\Models\\User')
                ->pluck('model_id');
            $userIdsToMigrate = $userIdsToMigrate->merge($refereeUserIds);
        }

        // Add new role to unique users
        $uniqueUserIds = $userIdsToMigrate->unique();
        foreach ($uniqueUserIds as $userId) {
            DB::table('model_has_roles')->insertOrIgnore([
                'role_id' => $technicalOfficialRole->id,
                'model_type' => 'App\\Models\\User',
                'model_id' => $userId,
            ]);
        }

        Log::info("Migrated {$uniqueUserIds->count()} users to individual-technical-official role");

        // Migrate users with view-individual-judge or view-individual-referee to view-individual-technical-official
        $viewUserIdsToMigrate = collect();

        if ($viewJudgeRole) {
            $viewJudgeUserIds = DB::table('model_has_roles')
                ->where('role_id', $viewJudgeRole->id)
                ->where('model_type', 'App\\Models\\User')
                ->pluck('model_id');
            $viewUserIdsToMigrate = $viewUserIdsToMigrate->merge($viewJudgeUserIds);
        }

        if ($viewRefereeRole) {
            $viewRefereeUserIds = DB::table('model_has_roles')
                ->where('role_id', $viewRefereeRole->id)
                ->where('model_type', 'App\\Models\\User')
                ->pluck('model_id');
            $viewUserIdsToMigrate = $viewUserIdsToMigrate->merge($viewRefereeUserIds);
        }

        // Add new view role to unique users
        $uniqueViewUserIds = $viewUserIdsToMigrate->unique();
        foreach ($uniqueViewUserIds as $userId) {
            DB::table('model_has_roles')->insertOrIgnore([
                'role_id' => $viewTechnicalOfficialRole->id,
                'model_type' => 'App\\Models\\User',
                'model_id' => $userId,
            ]);
        }

        Log::info("Migrated {$uniqueViewUserIds->count()} users to view-individual-technical-official role");
    }

    /**
     * Update license_roles pivot table entries
     */
    private function updateLicenseRoles(): void
    {
        $technicalOfficialRole = Role::where('name', 'individual-technical-official')->first();

        $judgeRole = Role::where('name', 'individual-judge')->first();
        $refereeRole = Role::where('name', 'individual-referee')->first();

        $updatedCount = 0;

        // Update license_roles entries from old roles to new role
        if ($judgeRole) {
            $count = DB::table('license_roles')
                ->where('role_id', $judgeRole->id)
                ->update(['role_id' => $technicalOfficialRole->id]);
            $updatedCount += $count;
        }

        if ($refereeRole) {
            // Get license IDs that already have the new role
            $existingLicenseIds = DB::table('license_roles')
                ->where('role_id', $technicalOfficialRole->id)
                ->pluck('license_id');

            // Update only those that don't have the new role yet
            $count = DB::table('license_roles')
                ->where('role_id', $refereeRole->id)
                ->whereNotIn('license_id', $existingLicenseIds)
                ->update(['role_id' => $technicalOfficialRole->id]);
            $updatedCount += $count;

            // Delete duplicates
            DB::table('license_roles')
                ->where('role_id', $refereeRole->id)
                ->delete();
        }

        Log::info("Updated {$updatedCount} license_roles entries to individual-technical-official");
    }

    /**
     * Update certification_roles pivot table entries
     */
    private function updateCertificationRoles(): void
    {
        $technicalOfficialRole = Role::where('name', 'individual-technical-official')->first();
        $viewTechnicalOfficialRole = Role::where('name', 'view-individual-technical-official')->first();

        $judgeRole = Role::where('name', 'individual-judge')->first();
        $refereeRole = Role::where('name', 'individual-referee')->first();
        $viewJudgeRole = Role::where('name', 'view-individual-judge')->first();
        $viewRefereeRole = Role::where('name', 'view-individual-referee')->first();

        $updatedCount = 0;

        // Update primary role entries
        if ($judgeRole) {
            $count = DB::table('certification_roles')
                ->where('role_id', $judgeRole->id)
                ->update(['role_id' => $technicalOfficialRole->id]);
            $updatedCount += $count;
        }

        if ($refereeRole) {
            $existingCertIds = DB::table('certification_roles')
                ->where('role_id', $technicalOfficialRole->id)
                ->pluck('certification_id');

            $count = DB::table('certification_roles')
                ->where('role_id', $refereeRole->id)
                ->whereNotIn('certification_id', $existingCertIds)
                ->update(['role_id' => $technicalOfficialRole->id]);
            $updatedCount += $count;

            DB::table('certification_roles')
                ->where('role_id', $refereeRole->id)
                ->delete();
        }

        // Update view role entries
        $viewUpdatedCount = 0;

        if ($viewJudgeRole) {
            $count = DB::table('certification_roles')
                ->where('role_id', $viewJudgeRole->id)
                ->update(['role_id' => $viewTechnicalOfficialRole->id]);
            $viewUpdatedCount += $count;
        }

        if ($viewRefereeRole) {
            $existingViewCertIds = DB::table('certification_roles')
                ->where('role_id', $viewTechnicalOfficialRole->id)
                ->pluck('certification_id');

            $count = DB::table('certification_roles')
                ->where('role_id', $viewRefereeRole->id)
                ->whereNotIn('certification_id', $existingViewCertIds)
                ->update(['role_id' => $viewTechnicalOfficialRole->id]);
            $viewUpdatedCount += $count;

            DB::table('certification_roles')
                ->where('role_id', $viewRefereeRole->id)
                ->delete();
        }

        Log::info("Updated {$updatedCount} certification_roles primary entries and {$viewUpdatedCount} view entries");
    }

    /**
     * Delete the old consolidated roles
     */
    private function deleteOldRoles(): void
    {
        foreach ($this->oldRoleNames as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                // Remove from model_has_roles
                DB::table('model_has_roles')
                    ->where('role_id', $role->id)
                    ->delete();

                // Remove from role_has_permissions
                DB::table('role_has_permissions')
                    ->where('role_id', $role->id)
                    ->delete();

                // Delete the role
                $role->delete();

                Log::info("Deleted old role: {$roleName}");
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * Note: Perfect restoration is not possible if users were assigned to both
     * judge AND referee roles before consolidation, as we cannot determine
     * which role(s) they originally had.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            // Step 1: Recreate old roles with their original permissions
            $this->recreateOldRoles();

            // Step 2: Migrate users back (note: may not be perfect restoration)
            $this->migrateUsersBack();

            // Step 3: Update pivot tables back
            $this->revertLicenseRoles();
            $this->revertCertificationRoles();

            // Step 4: Delete consolidated roles
            $this->deleteConsolidatedRoles();

            Log::info('Reverted technical official role consolidation');
        });

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Recreate the original judge and referee roles
     */
    private function recreateOldRoles(): void
    {
        // individual-judge
        $judgeRole = Role::firstOrCreate(
            ['name' => 'individual-judge', 'guard_name' => 'web']
        );
        $judgeRole->syncPermissions([
            'access judge menu',
            'access events',
            'access sport individual licenses attributed',
            'access referee-judge official documents',
            'access files area menu',
        ]);

        // individual-referee
        $refereeRole = Role::firstOrCreate(
            ['name' => 'individual-referee', 'guard_name' => 'web']
        );
        $refereeRole->syncPermissions([
            'access referee menu',
            'access events',
            'access sport individual licenses attributed',
            'access referee-judge official documents',
            'access files area menu',
        ]);

        // view-individual-judge
        $viewJudgeRole = Role::firstOrCreate(
            ['name' => 'view-individual-judge', 'guard_name' => 'web']
        );
        $viewJudgeRole->syncPermissions([
            'access judge menu',
            'access sport menu',
        ]);

        // view-individual-referee
        $viewRefereeRole = Role::firstOrCreate(
            ['name' => 'view-individual-referee', 'guard_name' => 'web']
        );
        $viewRefereeRole->syncPermissions([
            'access referee menu',
            'access sport menu',
        ]);

        Log::info('Recreated original judge and referee roles');
    }

    /**
     * Migrate users back to original roles
     *
     * Note: Users will be assigned to individual-judge by default
     * since we cannot determine original assignment
     */
    private function migrateUsersBack(): void
    {
        $technicalOfficialRole = Role::where('name', 'individual-technical-official')->first();
        $viewTechnicalOfficialRole = Role::where('name', 'view-individual-technical-official')->first();

        $judgeRole = Role::where('name', 'individual-judge')->first();
        $viewJudgeRole = Role::where('name', 'view-individual-judge')->first();

        if ($technicalOfficialRole) {
            $userIds = DB::table('model_has_roles')
                ->where('role_id', $technicalOfficialRole->id)
                ->where('model_type', 'App\\Models\\User')
                ->pluck('model_id');

            foreach ($userIds as $userId) {
                DB::table('model_has_roles')->insertOrIgnore([
                    'role_id' => $judgeRole->id,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $userId,
                ]);
            }
        }

        if ($viewTechnicalOfficialRole) {
            $viewUserIds = DB::table('model_has_roles')
                ->where('role_id', $viewTechnicalOfficialRole->id)
                ->where('model_type', 'App\\Models\\User')
                ->pluck('model_id');

            foreach ($viewUserIds as $userId) {
                DB::table('model_has_roles')->insertOrIgnore([
                    'role_id' => $viewJudgeRole->id,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $userId,
                ]);
            }
        }

        Log::info('Migrated users back to original roles (defaulted to judge roles)');
    }

    /**
     * Revert license_roles to use judge role
     */
    private function revertLicenseRoles(): void
    {
        $technicalOfficialRole = Role::where('name', 'individual-technical-official')->first();
        $judgeRole = Role::where('name', 'individual-judge')->first();

        if ($technicalOfficialRole) {
            DB::table('license_roles')
                ->where('role_id', $technicalOfficialRole->id)
                ->update(['role_id' => $judgeRole->id]);
        }
    }

    /**
     * Revert certification_roles to use judge roles
     */
    private function revertCertificationRoles(): void
    {
        $technicalOfficialRole = Role::where('name', 'individual-technical-official')->first();
        $viewTechnicalOfficialRole = Role::where('name', 'view-individual-technical-official')->first();
        $judgeRole = Role::where('name', 'individual-judge')->first();
        $viewJudgeRole = Role::where('name', 'view-individual-judge')->first();

        if ($technicalOfficialRole) {
            DB::table('certification_roles')
                ->where('role_id', $technicalOfficialRole->id)
                ->update(['role_id' => $judgeRole->id]);
        }

        if ($viewTechnicalOfficialRole) {
            DB::table('certification_roles')
                ->where('role_id', $viewTechnicalOfficialRole->id)
                ->update(['role_id' => $viewJudgeRole->id]);
        }
    }

    /**
     * Delete the consolidated technical official roles
     */
    private function deleteConsolidatedRoles(): void
    {
        $rolesToDelete = ['individual-technical-official', 'view-individual-technical-official'];

        foreach ($rolesToDelete as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                DB::table('model_has_roles')
                    ->where('role_id', $role->id)
                    ->delete();

                DB::table('role_has_permissions')
                    ->where('role_id', $role->id)
                    ->delete();

                $role->delete();

                Log::info("Deleted consolidated role: {$roleName}");
            }
        }
    }
};
