<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class FederationApplicationTemplatesMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the "Eventos" parent menu item in federation menu
        $eventosParent = MenuItem::where('menu_id', 2) // federation menu
            ->where('name', 'Eventos')
            ->whereNull('parent_id')
            ->first();

        if (! $eventosParent) {
            $this->command->error('Could not find "Eventos" parent menu item in federation menu');

            return;
        }

        // Check if menu item already exists
        $existingItem = MenuItem::where('route_name', 'federation.application-templates.index')->first();

        if ($existingItem) {
            $this->command->info('Menu item for federation application templates already exists');

            return;
        }

        // Create new menu item for Application Templates
        MenuItem::create([
            'menu_id' => 2, // federation menu
            'menu_group_id' => null,
            'parent_id' => $eventosParent->id,
            'committee_id' => null,
            'name' => 'Templates de Candidaturas',
            'icon' => null,
            'order' => 3, // After "Candidaturas" (1) and "Lista de Eventos" (2)
            'route_name' => 'federation.application-templates.index',
            'route_parameters' => [],
            'active_patterns' => ['application-templates'],
            'permissions' => [],
            'selected_roles' => null,
            'visibility_conditions' => [
                'type' => 'callback',
                'callback' => 'is_default_federation',
            ],
            'visible' => 1,
            'badge_config' => null,
            'translation_namespace' => null,
            'metadata' => [
                'is_default_federation_only' => true,
                'description' => 'Manage event application templates (main federation only)',
            ],
        ]);

        $this->command->info('Federation application templates menu item created successfully');
    }
}
