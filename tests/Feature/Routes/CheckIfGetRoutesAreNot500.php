<?php

use App\Models\User;
use Database\Factories\UserFactory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

beforeEach(function () {
    $this->withoutVite();

    $this->artisan('db:seed', ['--class' => 'UserGroupSeeder']);
    $this->artisan('db:seed', ['--class' => 'RoleAndPermissionSeeder']);
});

test('all static GET routes should not return 500 when accessed with correct credentials', function () {
    // Fetch all registered routes
    $routes = collect(\Route::getRoutes())->filter(function ($route) {
        return in_array('GET', $route->methods());
    })
        ->reject(function ($route) {
            // List of routes or route prefixes to exclude
            $excludedRoutes = [
                'debugbar',        // For Laravel Debugbar
                '_ignition',       // For Laravel Ignition (part of error handling)
                'clockwork',       // For Clockwork
                '_boost',          // For Laravel Boost
                'livewire',        // For Livewire internals
                'sanctum',         // For Sanctum internals
                'docs',            // Generated VitePress output is not tracked
            ];

            foreach ($excludedRoutes as $excludedRoute) {
                if (strpos($route->uri(), $excludedRoute) !== false) {
                    return true;  // Reject this route
                }
            }

            if (str_contains($route->uri(), '{')) {
                return true; // Dynamic model-bound routes need route-specific fixtures.
            }

            return false;
        })
        ->map(function ($route) {
            return [
                'method' => 'GET',
                'uri' => $route->uri(),
                'name' => $route->getName(),
            ];
        });

    foreach ($routes as $route) {
        // Determine the required user type based on the route prefix or other criteria
        $user = null;
        if (strpos($route['uri'], 'admin') !== false) {
            $user = UserFactory::new()->forGroup('ADMIN')->create();
        } elseif (strpos($route['uri'], 'federation') !== false) {
            $user = UserFactory::new()->forGroup('FEDERATION')->create();
        } elseif (strpos($route['uri'], 'entity') !== false) {
            $user = UserFactory::new()->forGroup('ENTITY')->create();
        } elseif (strpos($route['uri'], 'individual') !== false) {
            $user = UserFactory::new()->forGroup('INDIVIDUAL')->create();
        }

        // If the route requires authentication, authenticate as the determined user
        if ($user) {
            $this->actingAs($user);
        }

        // Handle dynamic routes e.g. {id}
        $uri = preg_replace('/\{.*?\}/', 1, $route['uri']); // replace all {params} with 1, which is a simplification. Adjust if necessary.

        // Make a GET request to the route
        $response = $this->get($uri);
        // Check if the response is an instance of BinaryFileResponse. If so, skip the assertion.
        $baseResponse = $response->baseResponse ?? $response;
        if ($baseResponse instanceof BinaryFileResponse || $baseResponse instanceof StreamedResponse) {
            continue;
        }
        $statusCode = $response->getStatusCode();

        // Assert the response does not fail with a server error.
        $message = 'Route '.($route['name'] ? $route['name'] : 'Unnamed')." (URI: {$route['uri']}) returned a {$response->getStatusCode()}";
        $this->assertNotSame(500, $statusCode, $message);
    }
})->group('RouteCheck');
