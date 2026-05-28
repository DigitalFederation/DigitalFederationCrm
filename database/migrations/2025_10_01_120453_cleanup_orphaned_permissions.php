<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Permissions to be deleted - verified as:
     * - 0 role assignments
     * - 0 direct user assignments
     * - 0 menu items references
     * - NOT used in controllers/routes/middleware
     */
    protected array $orphanedPermissions = [
        // Hyphenated duplicates (17)
        'access-certifications',
        'access-diving-certifications-attributed',
        'access-diving-location',
        'access-documents',
        'access-entities',
        'access-events',
        'access-federation-official-documents',
        'access-federations',
        'access-individuals',
        'access-licenses',
        'access-memberships',
        'access-official-documents',
        'access-products',
        'access-scientific-certifications-attributed',
        'access-settings',
        'access-sport-certifications-attributed',
        'access-users',

        // Unused CRUD hyphenated permissions (9)
        'create-entities',
        'create-individuals',
        'create-payment-documents',
        'delete-entities',
        'delete-individuals',
        'edit-entities',
        'edit-individuals',
        'impersonate-users',
        'manage-user-roles',

        // Unused manage hyphenated permissions (7)
        'manage-certifications',
        'manage-diving-locations',
        'manage-events',
        'manage-federations',
        'manage-licenses',
        'manage-memberships',
        'manage-products',
        'manage-settings',
        'manage-users',

        // Legacy/unused permissions (3)
        'access documents invoices',
        'download individual official documents',
    ];

    /**
     * Store deleted permissions for rollback
     */
    protected array $deletedPermissionsBackup = [];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->log('Starting orphaned permissions cleanup...');
        $this->log('Total permissions to check: ' . count($this->orphanedPermissions));

        foreach ($this->orphanedPermissions as $permissionName) {
            $this->deletePermissionIfSafe($permissionName);
        }

        $this->log('Cleanup completed!');
        $this->log('Permissions deleted: ' . count($this->deletedPermissionsBackup));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->log('Rolling back permission deletions...');

        foreach ($this->deletedPermissionsBackup as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'guard_name' => $permission['guard_name'],
            ]);
            $this->log("Restored permission: {$permission['name']}");
        }

        $this->log('Rollback completed!');
    }

    /**
     * Delete permission only if it's truly safe
     */
    protected function deletePermissionIfSafe(string $permissionName): void
    {
        $permission = Permission::where('name', $permissionName)->first();

        if (! $permission) {
            $this->log("⚠️  Permission not found: {$permissionName}");

            return;
        }

        // Safety check 1: Verify no role assignments
        $roleCount = DB::table('role_has_permissions')
            ->where('permission_id', $permission->id)
            ->count();

        if ($roleCount > 0) {
            $this->log("❌ SKIPPED: {$permissionName} (has {$roleCount} role assignments)");

            return;
        }

        // Safety check 2: Verify no direct user assignments
        $userCount = DB::table('model_has_permissions')
            ->where('permission_id', $permission->id)
            ->count();

        if ($userCount > 0) {
            $this->log("❌ SKIPPED: {$permissionName} (has {$userCount} direct user assignments)");

            return;
        }

        // Safety check 3: Verify not used in menu items
        $menuItemsCount = DB::table('menu_items')
            ->whereRaw('JSON_CONTAINS(permissions, ?)', [json_encode($permissionName)])
            ->count();

        if ($menuItemsCount > 0) {
            $this->log("❌ SKIPPED: {$permissionName} (used in {$menuItemsCount} menu items)");

            return;
        }

        // All checks passed - safe to delete
        // Backup permission for rollback
        $this->deletedPermissionsBackup[] = [
            'name' => $permission->name,
            'guard_name' => $permission->guard_name,
        ];

        // Delete permission
        $permission->delete();

        $this->log("✅ DELETED: {$permissionName}");
    }

    /**
     * Log message to console
     */
    protected function log(string $message): void
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            echo $message . PHP_EOL;
        }
    }
};
