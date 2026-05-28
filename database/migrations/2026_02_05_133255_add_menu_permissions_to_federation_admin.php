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
        $federationAdmin = Role::where('name', 'federation-admin')->first();

        if ($federationAdmin) {
            // Ensure permissions exist
            $permissions = [
                'access sport menu',
                'access diving menu',
                'access scientific menu',
            ];

            foreach ($permissions as $permissionName) {
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            }

            $federationAdmin->givePermissionTo($permissions);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $federationAdmin = Role::where('name', 'federation-admin')->first();

        if ($federationAdmin) {
            $federationAdmin->revokePermissionTo([
                'access sport menu',
                'access diving menu',
                'access scientific menu',
            ]);
        }
    }
};
