<?php

namespace App\Console\Commands;

use Domain\Documents\Models\Document;
use Domain\Entities\Models\Entity;
use Domain\Individuals\Models\Individual;
use Domain\Memberships\Models\MemberSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixEntityManagedDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:entity-managed-documents {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix documents that should belong to entities but are assigned to individuals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('Searching for entity-managed subscriptions with incorrect document ownership...');

        // Find subscriptions that are entity-managed but have documents assigned to individuals
        $subscriptions = MemberSubscription::query()
            ->where('member_type', Individual::class)
            ->whereHas('membershipPackage', function ($query) {
                $query->whereJsonContains('distribution_methods', 'entity_managed');
            })
            ->with(['membershipPackage', 'member'])
            ->get();

        $this->info("Found {$subscriptions->count()} entity-managed individual subscriptions");

        $fixedCount = 0;

        foreach ($subscriptions as $subscription) {
            // Find documents for this subscription
            $documents = Document::query()
                ->where('owner_type', MemberSubscription::class)
                ->where('owner_id', $subscription->id)
                ->whereHas('documentDetails', function ($query) use ($subscription) {
                    $query->where('owner_type', MemberSubscription::class)
                        ->where('owner_id', $subscription->id);
                })
                ->get();

            foreach ($documents as $document) {
                // Check if document is assigned to individual instead of entity
                if ($document->owner_type === Individual::class) {
                    // Find the entity that should own this document
                    // This requires checking the creation context
                    $individual = Individual::find($subscription->member_id);
                    $entity = $individual->entities()->first();

                    if ($entity) {
                        if ($dryRun) {
                            $this->line("Would fix document {$document->id}: Individual {$individual->name} -> Entity {$entity->name}");
                        } else {
                            DB::transaction(function () use ($document, $entity) {
                                $document->update([
                                    'owner_type' => Entity::class,
                                    'owner_id' => $entity->id,
                                ]);
                            });

                            $this->line("Fixed document {$document->id}: Reassigned to Entity {$entity->name}");
                        }

                        $fixedCount++;
                    }
                }
            }
        }

        if ($dryRun) {
            $this->info("Dry run completed. Would fix {$fixedCount} documents.");
            $this->info('Run without --dry-run to apply changes.');
        } else {
            $this->info("Fixed {$fixedCount} documents.");
        }

        return Command::SUCCESS;
    }
}
