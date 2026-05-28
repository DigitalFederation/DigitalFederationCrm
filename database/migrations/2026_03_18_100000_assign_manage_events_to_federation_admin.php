<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        Permission::firstOrCreate(
            ['name' => 'manage-events', 'guard_name' => 'web']
        );

        // Also ensure 'access events' exists
        Permission::firstOrCreate(
            ['name' => 'access events', 'guard_name' => 'web']
        );

        $federationAdmin = Role::where('name', 'federation-admin')->first();

        if ($federationAdmin) {
            $federationAdmin->givePermissionTo('manage-events');
            $federationAdmin->givePermissionTo('access events');
        }

        // Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        $federationAdmin = Role::where('name', 'federation-admin')->first();

        if ($federationAdmin) {
            $federationAdmin->revokePermissionTo('manage-events');
            $federationAdmin->revokePermissionTo('access events');
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
