<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the menu management permission
        Permission::firstOrCreate([
            'name' => 'manage_menus',
            'guard_name' => 'web',
        ]);

        // Assign to CMAS roles (assuming CMAS admin should have this permission)
        $cmasRoles = Role::whereIn('name', [
            'CMAS Administrator',
            'CMAS Super Admin',
            'System Administrator',
        ])->get();

        foreach ($cmasRoles as $role) {
            $role->givePermissionTo('manage_menus');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove permission from all roles
        $permission = Permission::where('name', 'manage_menus')->first();
        if ($permission) {
            // Remove from all roles
            $permission->roles()->detach();
            // Delete the permission
            $permission->delete();
        }
    }
};
