<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Find all roles in model_has_roles that don't have corresponding entries in user_role_sources
        // AND verify the user still exists
        $orphanedRoles = DB::table('model_has_roles as mhr')
            ->join('users as u', 'mhr.model_id', '=', 'u.id') // Only include if user exists
            ->leftJoin('user_role_sources as urs', function ($join) {
                $join->on('mhr.model_id', '=', 'urs.user_id')
                    ->on('mhr.role_id', '=', 'urs.role_id');
            })
            ->where('mhr.model_type', 'App\\Models\\User')
            ->whereNull('urs.id')
            ->select('mhr.model_id as user_id', 'mhr.role_id')
            ->get();

        $count = $orphanedRoles->count();

        // Also clean up orphaned roles for users that don't exist
        $deletedCount = DB::table('model_has_roles as mhr')
            ->leftJoin('users as u', 'mhr.model_id', '=', 'u.id')
            ->where('mhr.model_type', 'App\\Models\\User')
            ->whereNull('u.id')
            ->delete();

        if ($deletedCount > 0) {
            Log::info("Cleaned up {$deletedCount} roles for non-existent users from model_has_roles table.");
        }

        if ($count > 0) {
            Log::info("Found {$count} orphaned roles in model_has_roles table. Syncing to user_role_sources...");

            foreach ($orphanedRoles as $orphanedRole) {
                // Add the orphaned role to user_role_sources as manual source
                // since we don't know the original source
                try {
                    DB::table('user_role_sources')->insert([
                        'user_id' => $orphanedRole->user_id,
                        'role_id' => $orphanedRole->role_id,
                        'source_type' => 'manual',
                        'source_id' => null,
                        'assigned_at' => now(),
                        'assigned_by' => null, // Unknown who originally assigned it
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    Log::warning("Could not sync role for user {$orphanedRole->user_id}, role {$orphanedRole->role_id}: " . $e->getMessage());
                }
            }

            Log::info('Successfully synced orphaned roles to user_role_sources table.');
        } else {
            Log::info('No orphaned roles found. Database is consistent.');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to remove the synced entries on rollback
        // as they represent legitimate role assignments
        Log::info('Rollback of orphaned roles sync migration called. No action taken to preserve data integrity.');
    }
};
