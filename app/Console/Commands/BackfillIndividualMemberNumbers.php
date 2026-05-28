<?php

namespace App\Console\Commands;

use Domain\Individuals\Models\Individual;
use Domain\Memberships\Services\MemberNumberService;
use Illuminate\Console\Command;

class BackfillIndividualMemberNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'individuals:backfill-member-numbers
                            {--dry-run : Show what would be updated without making changes}
                            {--limit= : Limit the number of individuals to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign member numbers to existing individuals that do not have one';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $limit = $this->option('limit');

        $query = Individual::whereNull('member_number')
            ->orderBy('created_at', 'asc');

        if ($limit) {
            $query->limit((int) $limit);
        }

        $individuals = $query->get();
        $count = $individuals->count();

        if ($count === 0) {
            $this->info('No individuals found without member numbers.');

            return Command::SUCCESS;
        }

        $this->info("Found {$count} individual(s) without member numbers.");

        if ($dryRun) {
            $this->warn('Dry run mode - no changes will be made.');
            $this->table(
                ['ID', 'Name', 'Email', 'Created At'],
                $individuals->map(fn ($i) => [
                    $i->id,
                    $i->name . ' ' . $i->surname,
                    $i->email,
                    $i->created_at->format('Y-m-d H:i'),
                ])->toArray()
            );

            return Command::SUCCESS;
        }

        if (! $this->confirm("Do you want to assign member numbers to {$count} individual(s)?")) {
            $this->info('Operation cancelled.');

            return Command::SUCCESS;
        }

        $memberNumberService = new MemberNumberService;
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $assigned = 0;
        $skipped = 0;

        foreach ($individuals as $individual) {
            try {
                $memberNumberService->assignIndividualMemberNumber($individual);
                $individual->refresh();

                if ($individual->member_number !== null) {
                    $assigned++;
                } else {
                    $skipped++;
                    $this->newLine();
                    $this->warn("Skipped: {$individual->name} (ID: {$individual->id}) - already has member number");
                }
            } catch (\Exception $e) {
                $skipped++;
                $this->newLine();
                $this->error("Error assigning member number to {$individual->name} (ID: {$individual->id}): {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Completed: {$assigned} member number(s) assigned, {$skipped} skipped.");

        return Command::SUCCESS;
    }
}
