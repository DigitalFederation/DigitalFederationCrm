<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Get the individual-approved role ID
        $roleId = DB::table('roles')
            ->where('name', 'individual-approved')
            ->where('guard_name', 'web')
            ->value('id');

        if (! $roleId) {
            Log::warning('individual-approved role not found in roles table, skipping migration.');

            return;
        }

        // 2. Insert the federation_roles mapping (global, requires active membership)
        DB::table('federation_roles')->insertOrIgnore([
            'federation_id' => null,
            'role_id' => $roleId,
            'requires_active_membership' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Inserted individual-approved into federation_roles table.');

        // 3. Assign the role to all existing individual users who are missing it
        // Use raw DB queries to avoid lazy loading violations in migrations
        $modelType = (new User)->getMorphClass();

        $userIds = DB::table('users')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('individual')
                    ->whereColumn('individual.user_id', 'users.id');
            })
            ->whereNotExists(function ($query) use ($roleId, $modelType) {
                $query->select(DB::raw(1))
                    ->from('model_has_roles')
                    ->whereColumn('model_has_roles.model_id', 'users.id')
                    ->where('model_has_roles.model_type', $modelType)
                    ->where('model_has_roles.role_id', $roleId);
            })
            ->pluck('id');

        if ($userIds->isEmpty()) {
            Log::info('All individual users already have the individual-approved role.');

            return;
        }

        Log::info("Assigning individual-approved role to {$userIds->count()} existing users.");

        $inserts = $userIds->map(function ($userId) use ($roleId, $modelType) {
            return [
                'role_id' => $roleId,
                'model_type' => $modelType,
                'model_id' => $userId,
            ];
        })->toArray();

        // Insert in chunks to avoid query size limits
        foreach (array_chunk($inserts, 100) as $chunk) {
            DB::table('model_has_roles')->insertOrIgnore($chunk);
        }

        Log::info("Finished assigning individual-approved role to {$userIds->count()} users.");
    }

    public function down(): void
    {
        $roleId = DB::table('roles')
            ->where('name', 'individual-approved')
            ->where('guard_name', 'web')
            ->value('id');

        if ($roleId) {
            DB::table('federation_roles')
                ->whereNull('federation_id')
                ->where('role_id', $roleId)
                ->delete();
        }
    }
};
