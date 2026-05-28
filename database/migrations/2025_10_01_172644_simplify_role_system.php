<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Role mapping: old_name => new_name
        $roleMapping = [
            // Admin/CMAS roles -> admin
            'admin-super-admin' => 'admin',
            'cmas-super-admin' => 'admin',
            'admin-diving-admin' => 'admin',

            // Admin/CMAS roles -> association-sport-admin
            'admin-sport-admin' => 'association-sport-admin',
            'cmas-sport-admin' => 'association-sport-admin',

            // Admin/CMAS roles -> association-scientific-admin
            'admin-scientific-admin' => 'association-scientific-admin',
            'cmas-scientific-admin' => 'association-scientific-admin',

            // CMAS roles -> association-cmas-admin
            'cmas-diving-admin' => 'association-cmas-admin',

            // Federation roles -> association-sport-admin
            'federation-sport-admin' => 'association-sport-admin',

            // Federation roles -> association-scientific-admin
            'federation-scientific-admin' => 'association-scientific-admin',

            // Federation roles -> association-cmas-admin
            'federation-diving-admin' => 'association-cmas-admin',

            // Local federation roles -> association-territorial-admin
            'local-federation-admin' => 'association-territorial-admin',

            // Local federation roles -> association-sport-admin
            'local-federation-sport-admin' => 'association-sport-admin',

            // Local federation roles -> association-scientific-admin
            'local-federation-scientific-admin' => 'association-scientific-admin',

            // Local federation roles -> association-cmas-admin
            'local-federation-diving-admin' => 'association-cmas-admin',

            // Entity roles
            'entity-sport-admin' => 'entity-sport',
            'entity-diving-admin' => 'entity-diving-services',
            'entity-company' => 'entity-cmas',
        ];

        // Roles to delete (no equivalent in new system)
        $rolesToDelete = [
            'entity-scientific-admin',
            'entity-admin-operator',
        ];

        // First, handle merging roles that map to the same new role
        // This prevents unique constraint violations
        foreach ($roleMapping as $oldName => $newName) {
            // Check if target role already exists
            $existingRole = DB::table('roles')->where('name', $newName)->first();
            $oldRole = DB::table('roles')->where('name', $oldName)->first();

            if (! $oldRole) {
                continue; // Old role doesn't exist, skip
            }

            if ($existingRole && $oldRole->id !== $existingRole->id) {
                // Target role exists, we need to merge
                // Move all users from old role to new role (avoiding duplicates)
                // Using a workaround for MySQL's limitation on updating the same table in subquery

                // Get records to update
                $recordsToUpdate = DB::table('model_has_roles as mhr1')
                    ->select('mhr1.model_id', 'mhr1.model_type')
                    ->where('mhr1.role_id', $oldRole->id)
                    ->whereNotExists(function ($query) use ($existingRole) {
                        $query->select(DB::raw(1))
                            ->from('model_has_roles as mhr2')
                            ->whereColumn('mhr2.model_id', 'mhr1.model_id')
                            ->whereColumn('mhr2.model_type', 'mhr1.model_type')
                            ->where('mhr2.role_id', $existingRole->id);
                    })
                    ->get();

                // Update those records
                foreach ($recordsToUpdate as $record) {
                    DB::table('model_has_roles')
                        ->where('role_id', $oldRole->id)
                        ->where('model_type', $record->model_type)
                        ->where('model_id', $record->model_id)
                        ->update(['role_id' => $existingRole->id]);
                }

                // Delete remaining duplicate entries
                DB::table('model_has_roles')
                    ->where('role_id', $oldRole->id)
                    ->delete();

                // Move permissions from old role to new role (avoiding duplicates)
                $permIdsToUpdate = DB::table('role_has_permissions as rhp1')
                    ->where('rhp1.role_id', $oldRole->id)
                    ->whereNotExists(function ($query) use ($existingRole) {
                        $query->select(DB::raw(1))
                            ->from('role_has_permissions as rhp2')
                            ->whereColumn('rhp2.permission_id', 'rhp1.permission_id')
                            ->where('rhp2.role_id', $existingRole->id);
                    })
                    ->pluck('permission_id');

                // Update those permissions
                if ($permIdsToUpdate->isNotEmpty()) {
                    DB::table('role_has_permissions')
                        ->where('role_id', $oldRole->id)
                        ->whereIn('permission_id', $permIdsToUpdate)
                        ->update(['role_id' => $existingRole->id]);
                }

                // Delete remaining duplicate entries
                DB::table('role_has_permissions')
                    ->where('role_id', $oldRole->id)
                    ->delete();

                // Delete the old role
                DB::table('roles')->where('id', $oldRole->id)->delete();
            } else {
                // Target role doesn't exist, simple rename
                DB::table('roles')
                    ->where('name', $oldName)
                    ->update(['name' => $newName]);
            }
        }

        // Delete roles that have no equivalent
        foreach ($rolesToDelete as $roleName) {
            $role = DB::table('roles')->where('name', $roleName)->first();
            if ($role) {
                // Remove all user assignments
                DB::table('model_has_roles')->where('role_id', $role->id)->delete();

                // Remove all permission assignments
                DB::table('role_has_permissions')->where('role_id', $role->id)->delete();

                // Delete the role
                DB::table('roles')->where('id', $role->id)->delete();
            }
        }

        // Update route_permissions table middleware column if it contains old role names
        $this->updateRoutePermissionsMiddleware($roleMapping);
    }

    /**
     * Update middleware JSON in route_permissions table
     */
    private function updateRoutePermissionsMiddleware(array $roleMapping): void
    {
        $routePermissions = DB::table('route_permissions')->get();

        foreach ($routePermissions as $routePermission) {
            $middleware = json_decode($routePermission->middleware, true);

            if (! is_array($middleware)) {
                continue;
            }

            $updated = false;

            foreach ($middleware as $key => $middlewareItem) {
                // Check if middleware contains role references like "role:old-role-name"
                if (is_string($middlewareItem) && str_contains($middlewareItem, 'role:')) {
                    foreach ($roleMapping as $oldName => $newName) {
                        if (str_contains($middlewareItem, $oldName)) {
                            $middleware[$key] = str_replace($oldName, $newName, $middlewareItem);
                            $updated = true;
                        }
                    }
                }
            }

            if ($updated) {
                DB::table('route_permissions')
                    ->where('id', $routePermission->id)
                    ->update(['middleware' => json_encode($middleware)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse mapping: new_name => old_name
        // Note: Some roles merged into others, so we can only partially reverse
        $reverseMapping = [
            'admin' => 'admin-super-admin', // Will only restore one of the merged roles
            'association-sport-admin' => 'admin-sport-admin',
            'association-scientific-admin' => 'admin-scientific-admin',
            'association-cmas-admin' => 'cmas-diving-admin',
            'association-territorial-admin' => 'local-federation-admin',
            'entity-sport' => 'entity-sport-admin',
            'entity-diving-services' => 'entity-diving-admin',
            'entity-cmas' => 'entity-company',
        ];

        foreach ($reverseMapping as $newName => $oldName) {
            DB::table('roles')
                ->where('name', $newName)
                ->update(['name' => $oldName]);
        }

        // Note: Deleted roles (entity-scientific-admin, entity-admin-operator) cannot be restored
        // as their data was permanently removed
    }
};
