<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignIndividualApprovedRole extends Command
{
    protected $signature = 'users:assign-individual-approved-role
                            {--dry-run : Show how many users would be affected without making changes}';

    protected $description = 'Assign the individual-approved role to all users with individuals who are missing it';

    public function handle(): int
    {
        // Create the role if it doesn't exist
        $role = Role::firstOrCreate(['name' => 'individual-approved', 'guard_name' => 'web']);

        // Get all users that have individuals but don't have the individual-approved role
        $usersQuery = User::whereHas('individuals')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'individual-approved');
            });

        $count = $usersQuery->count();

        if ($count === 0) {
            $this->info('All individual users already have the individual-approved role.');

            return Command::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->info("Dry run: {$count} users would receive the 'individual-approved' role.");

            return Command::SUCCESS;
        }

        $this->info("Assigning 'individual-approved' role to {$count} users...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $usersQuery->chunk(100, function ($users) use ($role, $bar) {
            foreach ($users as $user) {
                $user->assignRole($role);
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Successfully assigned 'individual-approved' role to {$count} users.");

        return Command::SUCCESS;
    }
}
