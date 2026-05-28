<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create the permission if it doesn't exist
        $permission = Permission::firstOrCreate([
            'name' => 'manage_menus',
            'guard_name' => 'web',
        ]);

        // Assign to admin roles that should have this permission
        $adminRoles = [
            'admin',
            'cmas_admin',
            'super_admin',
            'system_admin',
        ];

        foreach ($adminRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role && ! $role->hasPermissionTo('manage_menus')) {
                $role->givePermissionTo('manage_menus');
                $this->command->info("✅ Assigned manage_menus permission to {$roleName} role");
            }
        }

        $this->command->info('✅ Menu permission seeder completed');
    }
}
