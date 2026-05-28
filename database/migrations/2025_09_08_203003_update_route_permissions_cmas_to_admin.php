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
        // Update all route_permissions that have user_group:CMAS to user_group:ADMIN
        DB::table('route_permissions')
            ->where('middleware', 'like', '%user_group:CMAS%')
            ->get()
            ->each(function ($permission) {
                $updatedMiddleware = str_replace('user_group:CMAS', 'user_group:ADMIN', $permission->middleware);

                DB::table('route_permissions')
                    ->where('id', $permission->id)
                    ->update(['middleware' => $updatedMiddleware]);
            });

        \Log::info('Updated route_permissions table: Changed all user_group:CMAS to user_group:ADMIN');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to CMAS if needed
        DB::table('route_permissions')
            ->where('middleware', 'like', '%user_group:ADMIN%')
            ->get()
            ->each(function ($permission) {
                $updatedMiddleware = str_replace('user_group:ADMIN', 'user_group:CMAS', $permission->middleware);

                DB::table('route_permissions')
                    ->where('id', $permission->id)
                    ->update(['middleware' => $updatedMiddleware]);
            });
    }
};
