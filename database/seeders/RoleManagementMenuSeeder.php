<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class RoleManagementMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Get the CMAS menu
        $cmasMenu = Menu::where('machine_name', 'cmas')->first();

        if (! $cmasMenu) {
            $this->command->error('CMAS menu not found. Please run the menu migration seeder first.');

            return;
        }

        // Find or create the Users parent menu item
        $usersMenuItem = $this->findOrCreateUsersMenuItem($cmasMenu);

        if (! $usersMenuItem) {
            return;
        }

        // Check if Role Management menu item already exists
        $existingRoleManagement = MenuItem::where('menu_id', $cmasMenu->id)
            ->where('parent_id', $usersMenuItem->id)
            ->where('route_name', 'cmas.role-management.index')
            ->first();

        if ($existingRoleManagement) {
            $this->command->info('Role Management menu item already exists. Updating...');

            $existingRoleManagement->update([
                'route_name' => 'cmas.role-management.index',
                'permissions' => ['access role management dashboard'],
                'active_patterns' => ['role-management'],
                'visible' => true,
                'order' => 10, // Position before other role items
            ]);
        } else {
            // Create the Role Management menu item
            MenuItem::create([
                'menu_id' => $cmasMenu->id,
                'parent_id' => $usersMenuItem->id,
                'name' => 'Gestão de Funções',
                'icon' => 'cog',
                'route_name' => 'cmas.role-management.index',
                'route_parameters' => null,
                'active_patterns' => ['role-management'],
                'permissions' => ['access role management dashboard'],
                'visibility_conditions' => null,
                'order' => 10, // Position before other role items
                'visible' => true,
                'committee_id' => null,
                'metadata' => [
                    'description' => 'Advanced role and permission management',
                    'feature' => 'role_management',
                    'version' => '1.0',
                ],
            ]);

            $this->command->info('Role Management menu item created successfully.');
        }

        // Update the Users menu to include role-management in active patterns
        $this->updateUsersMenuActivePatterns($usersMenuItem);

        // Update existing role-related menu items order
        $this->updateExistingRoleMenuItems($usersMenuItem->id);

        $this->command->info('Role Management menu integration completed.');
    }

    private function findOrCreateUsersMenuItem(Menu $cmasMenu): ?MenuItem
    {
        // Try to find existing Users menu item with different possible names
        $possibleNames = ['Utilizadores', 'Users', 'Usuários'];

        foreach ($possibleNames as $name) {
            $usersMenuItem = MenuItem::where('menu_id', $cmasMenu->id)
                ->where('name', $name)
                ->whereNull('parent_id')
                ->first();

            if ($usersMenuItem) {
                $this->command->info("Found Users menu item: {$name}");

                return $usersMenuItem;
            }
        }

        // If no Users menu found, create one
        $this->command->info('Users menu item not found. Creating one...');

        $usersMenuItem = MenuItem::create([
            'menu_id' => $cmasMenu->id,
            'parent_id' => null,
            'name' => 'Utilizadores',
            'icon' => 'users',
            'route_name' => null,
            'route_parameters' => null,
            'active_patterns' => ['users', 'role', 'role-management'],
            'permissions' => ['access users'],
            'visibility_conditions' => null,
            'order' => 50,
            'visible' => true,
            'committee_id' => null,
            'metadata' => [
                'description' => 'User and role management',
                'feature' => 'user_management',
                'version' => '1.0',
            ],
        ]);

        $this->command->info('Users menu item created successfully.');

        return $usersMenuItem;
    }

    private function updateUsersMenuActivePatterns(MenuItem $usersMenuItem): void
    {
        $currentPatterns = $usersMenuItem->active_patterns ?? [];

        // Ensure active_patterns is an array
        if (is_string($currentPatterns)) {
            $currentPatterns = json_decode($currentPatterns, true) ?: [];
        }

        if (! in_array('role-management', $currentPatterns)) {
            $currentPatterns[] = 'role-management';
            $usersMenuItem->update(['active_patterns' => $currentPatterns]);
            $this->command->info('Updated Users menu active patterns.');
        }
    }

    private function updateExistingRoleMenuItems(int $parentId): void
    {
        // Update Latest Users item
        MenuItem::where('parent_id', $parentId)
            ->where('name', 'Últimos Utilizadores')
            ->update(['order' => 20]);

        // Update Roles & Permissions item
        MenuItem::where('parent_id', $parentId)
            ->where('name', 'Funções & Permissões')
            ->update(['order' => 30]);

        // Update Role Mappings item
        MenuItem::where('parent_id', $parentId)
            ->where('name', 'Mapeamentos de Funções')
            ->update(['order' => 40]);

        $this->command->info('Updated existing role menu items order.');
    }
}
