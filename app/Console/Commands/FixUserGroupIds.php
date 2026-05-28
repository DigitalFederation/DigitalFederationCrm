<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixUserGroupIds extends Command
{
    protected $signature = 'user:fix-group-ids {--dry-run : Show what would be fixed without making changes}';

    protected $description = 'Fix users with invalid group_id (0 or NULL) by assigning the correct group based on their relationships';

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('Running in dry-run mode - no changes will be made.');
        }

        // Get group IDs
        $groups = Group::pluck('id', 'code')->toArray();

        if (empty($groups)) {
            $this->error('No groups found in the database. Please run seeders first.');

            return self::FAILURE;
        }

        $this->info('Found groups: ' . json_encode($groups));

        // Find users with invalid group_id (0 or NULL)
        $invalidUsers = User::where('group_id', 0)
            ->orWhereNull('group_id')
            ->with(['individuals', 'entities', 'federations'])
            ->get();

        if ($invalidUsers->isEmpty()) {
            $this->info('No users with invalid group_id found. Nothing to fix.');

            return self::SUCCESS;
        }

        $this->info("Found {$invalidUsers->count()} users with invalid group_id.");

        $fixed = [
            'individual' => 0,
            'entity' => 0,
            'federation' => 0,
            'unknown' => 0,
        ];

        $unknownUsers = [];

        DB::beginTransaction();

        try {
            foreach ($invalidUsers as $user) {
                $newGroupId = null;
                $type = 'unknown';

                // Determine the correct group based on relationships
                if ($user->individuals->isNotEmpty()) {
                    $newGroupId = $groups['INDIVIDUAL'] ?? null;
                    $type = 'individual';
                } elseif ($user->entities->isNotEmpty()) {
                    $newGroupId = $groups['ENTITY'] ?? null;
                    $type = 'entity';
                } elseif ($user->federations->isNotEmpty()) {
                    $newGroupId = $groups['FEDERATION'] ?? null;
                    $type = 'federation';
                }

                if ($newGroupId) {
                    if (! $isDryRun) {
                        $user->update(['group_id' => $newGroupId]);
                    }
                    $fixed[$type]++;
                    $this->line("  [{$type}] {$user->email} -> group_id: {$newGroupId}");
                } else {
                    $unknownUsers[] = $user->email;
                    $fixed['unknown']++;
                    $this->warn("  [unknown] {$user->email} - No relationship found, cannot determine group");
                }
            }

            if (! $isDryRun) {
                DB::commit();
                $this->newLine();
                $this->info('Changes committed to database.');
            } else {
                DB::rollBack();
                $this->newLine();
                $this->info('Dry-run complete. No changes were made.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());

            return self::FAILURE;
        }

        // Summary
        $this->newLine();
        $this->info('Summary:');
        $this->table(
            ['Type', 'Count'],
            [
                ['Individual', $fixed['individual']],
                ['Entity', $fixed['entity']],
                ['Federation', $fixed['federation']],
                ['Unknown (not fixed)', $fixed['unknown']],
            ]
        );

        if (! empty($unknownUsers)) {
            $this->newLine();
            $this->warn('The following users have no relationships and were not fixed:');
            foreach ($unknownUsers as $email) {
                $this->line("  - {$email}");
            }
            $this->newLine();
            $this->info('Consider manually reviewing these users or deleting them if they are orphaned.');
        }

        return self::SUCCESS;
    }
}
