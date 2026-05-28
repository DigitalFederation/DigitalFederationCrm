<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\User;
use Domain\Entities\Actions\AssociateUserToEntityAction;
use Domain\Entities\Models\Entity;
use Domain\Users\Actions\CreateUserAction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateUsersForEntities extends Command
{
    protected $signature = 'entities:create-users
                            {--dry-run : Show what would be created without actually creating}
                            {--entity-id= : Create user for a specific entity ID only}';

    protected $description = 'Create users for entities that do not have any associated users';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $specificEntityId = $this->option('entity-id');

        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $query = Entity::query()
            ->whereDoesntHave('users')
            ->whereNotNull('email')
            ->where('email', '!=', '');

        if ($specificEntityId) {
            $query->where('id', $specificEntityId);
        }

        $entities = $query->get();

        if ($entities->isEmpty()) {
            $this->info('No entities found without users.');

            return Command::SUCCESS;
        }

        $this->info("Found {$entities->count()} entities without users.");

        $entityGroup = Group::where('code', 'ENTITY')->first();
        if (! $entityGroup) {
            $this->error('ENTITY group not found. Please run seeders first.');

            return Command::FAILURE;
        }

        $created = 0;
        $skipped = 0;
        $errors = 0;

        $progressBar = $this->output->createProgressBar($entities->count());
        $progressBar->start();

        foreach ($entities as $entity) {
            try {
                $email = $entity->email;

                // Check if user already exists with this email
                $existingUser = User::where('email', $email)->first();

                if ($existingUser) {
                    if ($dryRun) {
                        $this->newLine();
                        $this->line("  Would associate existing user {$email} to entity: {$entity->name}");
                    } else {
                        // Associate existing user to entity
                        $associateUserToEntity = new AssociateUserToEntityAction;
                        $associateUserToEntity($existingUser, $entity, 'entity-admin');
                    }
                    $created++;
                } else {
                    if ($dryRun) {
                        $this->newLine();
                        $this->line("  Would create user for entity: {$entity->name} ({$email})");
                    } else {
                        // Create new user
                        $createUser = new CreateUserAction;
                        $createUserResult = $createUser([
                            'name' => $entity->name,
                            'email' => $email,
                            'group_id' => $entityGroup->id,
                            'bypass_verification' => true,
                        ], true);

                        $user = $createUserResult['user'];

                        // Associate user to entity
                        $associateUserToEntity = new AssociateUserToEntityAction;
                        $associateUserToEntity($user, $entity, 'entity-admin');
                    }
                    $created++;
                }
            } catch (\Exception $e) {
                $errors++;
                Log::error('Failed to create user for entity', [
                    'entity_id' => $entity->id,
                    'entity_name' => $entity->name,
                    'email' => $entity->email,
                    'error' => $e->getMessage(),
                ]);
                $this->newLine();
                $this->error("  Error for entity {$entity->name}: {$e->getMessage()}");
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info('Summary:');
        $this->info("  - Users created/associated: {$created}");
        $this->info("  - Errors: {$errors}");

        if ($dryRun) {
            $this->newLine();
            $this->warn('This was a dry run. Run without --dry-run to actually create users.');
        }

        return $errors > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
