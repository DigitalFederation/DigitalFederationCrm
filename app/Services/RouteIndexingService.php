<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class RouteIndexingService
{
    protected $routes = [];

    public function __construct($group)
    {

        $this->indexRoutes($group);
    }

    protected function indexRoutes($group)
    {

        $this->loadGroupRoutes($group);

        foreach (Route::getRoutes() as $route) {
            if ($routeName = $route->getName()) {
                $description = $this->getDescriptionFromRouteName($routeName);
                if ($description) {
                    $this->routes[$routeName] = [
                        'uri' => $route->uri(),
                        'name' => $routeName,
                        'description' => $description,
                    ];
                }
            }
        }

        // Remove duplicates if any
        $this->routes = array_unique($this->routes, SORT_REGULAR);
    }

    protected function loadGroupRoutes($group)
    {
        // Reset existing routes
        // Note: Implement a way to reset the routes, depending on your Laravel setup

        $routeFile = match ($group) {
            'admin' => base_path('routes/routes_admin.php'),
            'cmas' => base_path('routes/routes_admin.php'), // Backward compatibility
            'federation' => base_path('routes/routes_federation.php'),
            'entity' => base_path('routes/routes_entity.php'),
            'individual' => base_path('routes/routes_individual.php'),
            default => base_path('routes/routes_individual.php'),
        };

        if (File::exists($routeFile)) {
            require $routeFile;
        }
    }

    protected function getDescriptionFromRouteName($routeName)
    {
        $namingConventions = [
            // General Routes
            'dashboard' => 'Dashboard',
            'profile' => 'Profile Management',
            'profile.edit' => 'Edit Profile',
            'profile.update' => 'Update Profile',
            'login' => 'Login',
            'logout' => 'Logout',
            // Admin specific routes (formerly CMAS)
            'admin' => 'Admin Dashboard',
            'admin.dashboard' => 'Admin Dashboard',
            'admin.roles' => 'Admin Roles Management',
            'admin.users' => 'Admin User Management',
            'admin.federation' => 'Federation Management',
            'admin.entity' => 'Entity Management',
            'admin.individual' => 'Individual Management',
            'admin.evt-events' => 'Event Management',
            'admin.certification' => 'Certification Management',
            'admin.license' => 'License Management',
            'admin.official-documents' => 'Official Documents',
            'admin.anti-doping' => 'Anti-Doping Management',
            'admin.anti-doping.pin' => 'Anti-Doping PIN Management',
            'admin.anti-doping.index' => 'Anti-Doping Index',
            // Shipping and Pricing
            'admin.shipping' => 'Shipping Management',
            'admin.shipping.methods' => 'Shipping Methods',
            'admin.shipping.prices' => 'Shipping Prices',

            // Federation specific routes
            'federation' => 'Federation Dashboard',
            'federation.dashboard' => 'Federation Dashboard',
            'federation.profile' => 'Federation Profile Management',
            'federation.official-documents' => 'Official Documents',
            'federation.license-attributed' => 'Attributed Licenses',
            'federation.certification-attributed' => 'Attributed Certifications',
            'federation.evt-events' => 'Event List',

            // Entity specific routes
            'entity' => 'Entity Dashboard',
            'entity.dashboard' => 'Entity Dashboard',
            'entity.profile' => 'Entity Profile Management',
            'entity.official-documents' => 'Entity Official Documents',
            'entity.license-attributed' => 'Attributed Licenses',

            // Individual specific routes
            'individual' => 'Individual Dashboard',
            'individual.dashboard' => 'Individual Dashboard',
            'individual.profile' => 'Individual Profile Management',
            'individual.official-documents' => 'Individual Official Documents',

            // Enrollment Routes
            'enrollments' => 'Enrollment Management',
            'enrollments.store' => 'Store Enrollments',
            'enrollments.edit' => 'Edit Enrollments',

        ];

        $segments = explode('.', $routeName);
        $descriptionParts = [];

        foreach ($segments as $segment) {
            if (array_key_exists($segment, $namingConventions)) {
                $descriptionParts[] = $namingConventions[$segment];
            } else {
                // Optionally handle dynamic segments or unmapped segments here
                $descriptionParts[] = $segment;
            }
        }

        return implode(' ', $descriptionParts);
    }

    public function getRoutes()
    {
        return $this->routes;
    }
}
