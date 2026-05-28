<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionManagementMenuSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🔧 Starting Permission Management Menu Seeder...');

        // Create necessary permissions
        $permissions = [
            [
                'name' => 'manage-permissions',
                'description' => 'Create, edit, and delete permissions',
                'category' => 'Permissions',
            ],
            [
                'name' => 'view-permissions',
                'description' => 'View permissions list and details',
                'category' => 'Permissions',
            ],
            [
                'name' => 'manage-route-permissions',
                'description' => 'Manage route-permission mappings',
                'category' => 'Permissions',
            ],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                [
                    'guard_name' => 'web',
                    'description' => $permissionData['description'],
                    'category' => $permissionData['category'],
                ]
            );
            $this->command->info("✅ Created permission: {$permissionData['name']}");
        }

        // Find the CMAS menu
        $cmasMenu = Menu::where('machine_name', 'cmas')->first();
        if (! $cmasMenu) {
            $this->command->error('❌ CMAS menu not found!');

            return;
        }

        // Find the Users parent menu item
        $usersMenuItem = MenuItem::where('menu_id', $cmasMenu->id)
            ->where('route_name', 'cmas.users.index')
            ->first();

        if (! $usersMenuItem) {
            $this->command->error('❌ Users menu item not found!');

            return;
        }

        // Get the order for the new menu item (after Role Management)
        $roleManagementItem = MenuItem::where('menu_id', $cmasMenu->id)
            ->where('parent_id', $usersMenuItem->id)
            ->where('route_name', 'cmas.role-management.index')
            ->first();

        $order = $roleManagementItem ? $roleManagementItem->order + 1 : 2;

        // Update order of existing items after this position
        MenuItem::where('menu_id', $cmasMenu->id)
            ->where('parent_id', $usersMenuItem->id)
            ->where('order', '>=', $order)
            ->increment('order');

        // Create Permission Management menu item
        $permissionManagementItem = MenuItem::firstOrCreate(
            [
                'menu_id' => $cmasMenu->id,
                'route_name' => 'cmas.permission-management.index',
            ],
            [
                'parent_id' => $usersMenuItem->id,
                'name' => 'Permission Management',
                'icon' => 'heroicon-o-key',
                'order' => $order,
                'permissions' => ['manage-permissions', 'view-permissions'],
                'visible' => true,
                'active_patterns' => ['cmas/permission-management*'],
            ]
        );

        $this->command->info('✅ Created Permission Management menu item');

        // Create Route Permissions menu item
        $routePermissionsItem = MenuItem::firstOrCreate(
            [
                'menu_id' => $cmasMenu->id,
                'route_name' => 'cmas.route-permissions.index',
            ],
            [
                'parent_id' => $usersMenuItem->id,
                'name' => 'Route Permissions',
                'icon' => 'heroicon-o-shield-check',
                'order' => $order + 1,
                'permissions' => ['manage-route-permissions'],
                'visible' => true,
                'active_patterns' => ['cmas/route-permissions*'],
            ]
        );

        $this->command->info('✅ Created Route Permissions menu item');

        // Assign permissions to admin roles
        $adminRoles = [
            'admin',
            'association-sport-admin',
            'association-scientific-admin',
            'association-cmas-admin',
        ];

        foreach ($adminRoles as $roleName) {
            $role = \App\Models\Role::where('name', $roleName)->first();
            if ($role) {
                foreach (['manage-permissions', 'view-permissions', 'manage-route-permissions'] as $permission) {
                    if (! $role->hasPermissionTo($permission)) {
                        $role->givePermissionTo($permission);
                        $this->command->info("✅ Assigned {$permission} to {$roleName}");
                    }
                }
            }
        }

        // Clear menu cache
        $cmasMenu->clearCache();

        $this->command->info('✅ Permission Management menu setup completed!');
    }
}
