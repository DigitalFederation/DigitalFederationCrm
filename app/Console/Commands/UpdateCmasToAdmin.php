<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCmasToAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:cmas-to-admin 
                            {--check : Check current status without making changes}
                            {--rollback : Rollback the changes from ADMIN to CMAS}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update CMAS group code to ADMIN to complete the migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('check')) {
            return $this->checkStatus();
        }

        if ($this->option('rollback')) {
            return $this->rollbackChanges();
        }

        return $this->performMigration();
    }

    /**
     * Check the current status of groups
     */
    private function checkStatus()
    {
        $this->info('Checking current group status...');

        $groups = DB::table('user_group')->get();

        $this->table(
            ['ID', 'Name', 'Code'],
            $groups->map(function ($group) {
                return [$group->id, $group->name, $group->code];
            })->toArray()
        );

        $cmasGroup = $groups->firstWhere('code', 'CMAS');
        $adminGroup = $groups->firstWhere('code', 'ADMIN');

        if ($cmasGroup) {
            $this->warn("CMAS group still exists with ID: {$cmasGroup->id}");
            $userCount = DB::table('users')->where('group_id', $cmasGroup->id)->count();
            $this->info("Users with CMAS group: {$userCount}");
        }

        if ($adminGroup) {
            $this->info("ADMIN group exists with ID: {$adminGroup->id}");
            $userCount = DB::table('users')->where('group_id', $adminGroup->id)->count();
            $this->info("Users with ADMIN group: {$userCount}");
        }

        return Command::SUCCESS;
    }

    /**
     * Perform the migration from CMAS to ADMIN
     */
    private function performMigration()
    {
        $this->info('Starting CMAS to ADMIN migration...');

        // Check if CMAS group exists
        $cmasGroup = DB::table('user_group')->where('code', 'CMAS')->first();

        if (! $cmasGroup) {
            $this->warn('CMAS group not found. Migration may have already been completed.');

            // Check if ADMIN group exists
            $adminGroup = DB::table('user_group')->where('code', 'ADMIN')->first();
            if ($adminGroup) {
                $this->info('ADMIN group already exists. No action needed.');

                return Command::SUCCESS;
            }

            $this->error('Neither CMAS nor ADMIN group found. Please check your database.');

            return Command::FAILURE;
        }

        // Check for existing ADMIN group (shouldn't exist if CMAS exists)
        $existingAdmin = DB::table('user_group')->where('code', 'ADMIN')->first();
        if ($existingAdmin) {
            $this->error('ADMIN group already exists alongside CMAS group. Please resolve this conflict manually.');

            return Command::FAILURE;
        }

        // Count affected users
        $affectedUsers = DB::table('users')->where('group_id', $cmasGroup->id)->count();

        $this->info("Found CMAS group with ID: {$cmasGroup->id}");
        $this->info("Affected users: {$affectedUsers}");

        if (! $this->confirm('Do you want to proceed with the migration?')) {
            $this->info('Migration cancelled.');

            return Command::FAILURE;
        }

        try {
            DB::beginTransaction();

            // Update the group
            DB::table('user_group')
                ->where('id', $cmasGroup->id)
                ->update([
                    'code' => 'ADMIN',
                    'name' => 'Admin',
                ]);

            DB::commit();

            $this->info('✓ Successfully updated CMAS group to ADMIN');
            $this->info("✓ {$affectedUsers} users now have ADMIN group");

            // Clear any cached data
            if (function_exists('cache')) {
                cache()->flush();
                $this->info('✓ Cache cleared');
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Rollback changes from ADMIN to CMAS
     */
    private function rollbackChanges()
    {
        $this->info('Starting rollback from ADMIN to CMAS...');

        $adminGroup = DB::table('user_group')->where('code', 'ADMIN')->where('id', 4)->first();

        if (! $adminGroup) {
            $this->warn('ADMIN group (ID: 4) not found. Rollback may not be needed.');

            return Command::SUCCESS;
        }

        $affectedUsers = DB::table('users')->where('group_id', $adminGroup->id)->count();

        $this->info("Found ADMIN group with ID: {$adminGroup->id}");
        $this->info("Affected users: {$affectedUsers}");

        if (! $this->confirm('Do you want to rollback to CMAS?')) {
            $this->info('Rollback cancelled.');

            return Command::FAILURE;
        }

        try {
            DB::beginTransaction();

            DB::table('user_group')
                ->where('id', 4)
                ->where('code', 'ADMIN')
                ->update([
                    'code' => 'CMAS',
                    'name' => 'Cmas',
                ]);

            DB::commit();

            $this->info('✓ Successfully rolled back ADMIN group to CMAS');

            // Clear any cached data
            if (function_exists('cache')) {
                cache()->flush();
                $this->info('✓ Cache cleared');
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Rollback failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
