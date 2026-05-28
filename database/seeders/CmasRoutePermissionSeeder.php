<?php

namespace Database\Seeders;

use App\Models\RoutePermission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

/**
 * CMAS Route Permission Seeder
 *
 * Seeds route permissions for all CMAS (international) namespace routes.
 * All CMAS routes require the 'access international licenses' permission.
 */
class CmasRoutePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the permission exists
        $permission = Permission::firstOrCreate(
            ['name' => 'access international licenses', 'guard_name' => 'web'],
            ['category' => 'CMAS', 'description' => 'Access international CMAS licenses and certifications']
        );

        $this->command->info('Seeding CMAS route permissions...');

        // Define all CMAS route patterns
        $routes = [
            // Individual namespace - International licenses and certifications
            'cmas/individual/licenses-attributed',
            'cmas/individual/licenses-attributed/*',
            'cmas/individual/license-purchase',
            'cmas/individual/license-purchase/*',
            'cmas/individual/certifications',
            'cmas/individual/certifications/*',
            'cmas/individual/certification-card',
            'cmas/individual/certification-card/*',

            // Entity namespace - International licenses and certifications for entities
            'cmas/entity/licenses-attributed',
            'cmas/entity/licenses-attributed/*',
            'cmas/entity/license-purchase',
            'cmas/entity/license-purchase/*',
            'cmas/entity/certifications',
            'cmas/entity/certifications/*',
            'cmas/entity/member-licenses',
            'cmas/entity/member-licenses/*',

            // Federation namespace - International license and certification management
            'cmas/federation/licenses-attributed',
            'cmas/federation/licenses-attributed/*',
            'cmas/federation/certifications-attributed',
            'cmas/federation/certifications-attributed/*',
        ];

        $createdCount = 0;
        $existingCount = 0;

        foreach ($routes as $route) {
            $routePermission = RoutePermission::updateOrCreate(
                [
                    'route_pattern' => $route,
                    'permission_name' => 'access international licenses',
                ],
                [
                    'middleware' => ['auth', 'permission:access international licenses'],
                    'is_active' => true,
                ]
            );

            if ($routePermission->wasRecentlyCreated) {
                $createdCount++;
            } else {
                $existingCount++;
            }
        }

        $this->command->info('CMAS route permissions seeded successfully!');
        $this->command->info("Created: {$createdCount} | Updated: {$existingCount}");
    }
}
