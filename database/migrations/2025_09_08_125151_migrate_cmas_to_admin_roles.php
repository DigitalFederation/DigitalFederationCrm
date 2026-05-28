<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update roles table - replace cmas with admin
        DB::table('roles')
            ->whereIn('name', [
                'cmas-diving-admin',
                'cmas-notifications',
                'cmas-scientific-admin',
                'cmas-sport-admin',
                'cmas-super-admin',
                'entity-cmas-operator',
            ])
            ->get()
            ->each(function ($role) {
                $newName = str_replace('cmas', 'admin', $role->name);
                DB::table('roles')
                    ->where('id', $role->id)
                    ->update(['name' => $newName]);
            });

        // Update permissions table - replace cmas with admin if any exist
        DB::table('permissions')
            ->where('name', 'like', '%cmas%')
            ->get()
            ->each(function ($permission) {
                $newName = str_replace('cmas', 'admin', $permission->name);
                DB::table('permissions')
                    ->where('id', $permission->id)
                    ->update(['name' => $newName]);
            });

        // Update menu_items table if they reference CMAS routes
        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')
                ->where('route_name', 'like', 'cmas.%')
                ->update([
                    'route_name' => DB::raw("REPLACE(route_name, 'cmas.', 'admin.')"),
                ]);
        }

        // Update route_permissions table if it exists
        if (Schema::hasTable('route_permissions')) {
            DB::table('route_permissions')
                ->where('route_pattern', 'like', 'cmas.%')
                ->update([
                    'route_pattern' => DB::raw("REPLACE(route_pattern, 'cmas.', 'admin.')"),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert roles table - replace admin back to cmas
        DB::table('roles')
            ->whereIn('name', [
                'admin-diving-admin',
                'admin-notifications',
                'admin-scientific-admin',
                'admin-sport-admin',
                'admin-super-admin',
                'entity-admin-operator',
            ])
            ->get()
            ->each(function ($role) {
                $newName = str_replace('admin', 'cmas', $role->name);
                DB::table('roles')
                    ->where('id', $role->id)
                    ->update(['name' => $newName]);
            });

        // Revert permissions table - replace admin back to cmas if any exist
        DB::table('permissions')
            ->where('name', 'like', '%admin%')
            ->get()
            ->each(function ($permission) {
                $newName = str_replace('admin', 'cmas', $permission->name);
                DB::table('permissions')
                    ->where('id', $permission->id)
                    ->update(['name' => $newName]);
            });

        // Revert menu_items table if they reference admin routes
        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')
                ->where('route_name', 'like', 'admin.%')
                ->update([
                    'route_name' => DB::raw("REPLACE(route_name, 'admin.', 'cmas.')"),
                ]);
        }

        // Revert route_permissions table if it exists
        if (Schema::hasTable('route_permissions')) {
            DB::table('route_permissions')
                ->where('route_pattern', 'like', 'admin.%')
                ->update([
                    'route_pattern' => DB::raw("REPLACE(route_pattern, 'admin.', 'cmas.')"),
                ]);
        }
    }
};
