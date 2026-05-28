<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuMigrationSeeder extends Seeder
{
    /**
     * Mapping of menu machine names to display names
     */
    private array $menuDefinitions = [
        'cmas' => [
            'name' => 'CMAS Admin Menu',
            'description' => 'Main navigation menu for CMAS administrators',
        ],
        'federation' => [
            'name' => 'Federation Menu',
            'description' => 'Navigation menu for federation users',
        ],
        'entity' => [
            'name' => 'Entity Menu',
            'description' => 'Navigation menu for entity administrators',
        ],
        'individual' => [
            'name' => 'Individual Menu',
            'description' => 'Navigation menu for individual users',
        ],
    ];

    /**
     * Committee name to ID mapping cache
     */
    private array $committeeCache = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting menu migration from config/menu.php...');

        // Load the existing menu configuration
        $menuConfig = config('menu', []);

        if (empty($menuConfig)) {
            $this->command->error('No menu configuration found in config/menu.php');

            return;
        }

        // Cache committee lookups
        $this->buildCommitteeCache();

        DB::beginTransaction();

        try {
            foreach ($menuConfig as $machineName => $menuItems) {
                $this->migrateMenu($machineName, $menuItems);
            }

            DB::commit();
            $this->command->info('Menu migration completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Menu migration failed: ' . $e->getMessage());
            Log::error('Menu migration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Migrate a single menu
     */
    private function migrateMenu(string $machineName, array $menuItems): void
    {
        $this->command->info("Migrating menu: {$machineName}");

        // Create or find the menu
        $menuData = $this->menuDefinitions[$machineName] ?? [
            'name' => ucfirst($machineName) . ' Menu',
            'description' => "Navigation menu for {$machineName} users",
        ];

        $menu = Menu::updateOrCreate(
            ['machine_name' => $machineName],
            [
                'name' => $menuData['name'],
                'description' => $menuData['description'],
                'active' => true,
            ]
        );

        // Clear existing menu items for clean migration
        MenuItem::where('menu_id', $menu->id)->delete();

        // Migrate menu items
        $this->migrateMenuItems($menu, $menuItems);

        $this->command->info("✓ Migrated {$machineName} menu with " . count($menuItems) . ' items');
    }

    /**
     * Migrate menu items for a menu
     */
    private function migrateMenuItems(Menu $menu, array $items, ?int $parentId = null, int $startOrder = 0): void
    {
        $order = $startOrder;

        foreach ($items as $item) {
            $menuItem = $this->createMenuItem($menu, $item, $parentId, $order);

            // Handle children if they exist
            if (isset($item['children']) && is_array($item['children'])) {
                $this->migrateMenuItems($menu, $item['children'], $menuItem->id, 0);
            }

            $order++;
        }
    }

    /**
     * Create a single menu item
     */
    private function createMenuItem(Menu $menu, array $item, ?int $parentId, int $order): MenuItem
    {
        // Extract route information
        $routeData = $this->extractRouteData($item);

        // Extract permissions
        $permissions = $this->extractPermissions($item);

        // Extract active patterns
        $activePatterns = $item['active'] ?? [];
        if (is_string($activePatterns)) {
            $activePatterns = [$activePatterns];
        }

        // Determine committee ID if applicable
        $committeeId = $this->determineCommitteeId($item, $routeData);

        // Create the menu item
        return MenuItem::create([
            'menu_id' => $menu->id,
            'parent_id' => $parentId,
            'committee_id' => $committeeId,
            'name' => $item['name'] ?? 'Unnamed Item',
            'icon' => $item['icon'] ?? null,
            'order' => $order,
            'route_name' => $routeData['route_name'],
            'route_parameters' => $routeData['route_parameters'],
            'active_patterns' => $activePatterns,
            'permissions' => $permissions,
            'visible' => true,
            'metadata' => [
                'migrated_from_config' => true,
                'original_item' => $item,
            ],
        ]);
    }

    /**
     * Extract route data from menu item
     */
    private function extractRouteData(array $item): array
    {
        $route = $item['route'] ?? '';

        if (empty($route)) {
            return ['route_name' => null, 'route_parameters' => null];
        }

        // Handle different route formats
        if (is_string($route)) {
            return ['route_name' => $route, 'route_parameters' => null];
        }

        if (is_array($route)) {
            $routeName = $route[0] ?? null;
            $routeParameters = null;

            // Handle route parameters
            if (isset($route[1])) {
                if (is_array($route[1])) {
                    $routeParameters = $route[1];
                } else {
                    // Convert old format to new format
                    $routeParameters = [$route[1]];
                }
            }

            return [
                'route_name' => $routeName,
                'route_parameters' => $routeParameters,
            ];
        }

        return ['route_name' => null, 'route_parameters' => null];
    }

    /**
     * Extract permissions from menu item
     */
    private function extractPermissions(array $item): ?array
    {
        $can = $item['can'] ?? null;

        if (empty($can)) {
            return null;
        }

        if (is_string($can)) {
            return [$can];
        }

        if (is_array($can)) {
            return $can;
        }

        return null;
    }

    /**
     * Determine committee ID from various sources
     */
    private function determineCommitteeId(array $item, array $routeData): ?int
    {
        // Check if item has explicit committee
        if (isset($item['committee'])) {
            return $this->getCommitteeId($item['committee']);
        }

        // Check route parameters for committee filters
        if ($routeData['route_parameters']) {
            foreach ($routeData['route_parameters'] as $key => $value) {
                if (str_contains($key, 'committee')) {
                    // Handle committee filters in route parameters
                    if (is_string($value)) {
                        $committees = explode(',', $value);
                        // Return first committee ID (we can enhance this later)
                        if (! empty($committees)) {
                            return $this->getCommitteeId($committees[0]);
                        }
                    }
                }
            }
        }

        // Check route name for committee indicators
        if ($routeData['route_name']) {
            $routeName = $routeData['route_name'];
            if (str_contains($routeName, 'diving')) {
                return $this->getCommitteeId('diving');
            }
            if (str_contains($routeName, 'scientific')) {
                return $this->getCommitteeId('scientific');
            }
            if (str_contains($routeName, 'sport')) {
                return $this->getCommitteeId('sport');
            }
        }

        // Check permissions for committee indicators
        $permissions = $this->extractPermissions($item);
        if ($permissions) {
            foreach ($permissions as $permission) {
                if (str_contains($permission, 'diving')) {
                    return $this->getCommitteeId('diving');
                }
                if (str_contains($permission, 'scientific')) {
                    return $this->getCommitteeId('scientific');
                }
                if (str_contains($permission, 'sport')) {
                    return $this->getCommitteeId('sport');
                }
            }
        }

        return null;
    }

    /**
     * Get committee ID by name or code
     */
    private function getCommitteeId(string $identifier): ?int
    {
        $identifier = strtolower(trim($identifier));

        // Check cache first
        if (isset($this->committeeCache[$identifier])) {
            return $this->committeeCache[$identifier];
        }

        // Try to find committee
        $committee = Committee::where('code', strtoupper($identifier))
            ->orWhere('name', 'like', "%{$identifier}%")
            ->first();

        $id = $committee?->id;
        $this->committeeCache[$identifier] = $id;

        return $id;
    }

    /**
     * Build committee cache for faster lookups
     */
    private function buildCommitteeCache(): void
    {
        $committees = Committee::all();

        foreach ($committees as $committee) {
            $this->committeeCache[strtolower($committee->code)] = $committee->id;
            $this->committeeCache[strtolower($committee->name)] = $committee->id;
        }
    }
}
