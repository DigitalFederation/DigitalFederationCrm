<?php

namespace App\Console\Commands;

use Domain\EvtEvents\Actions\BulkDuplicateDisciplinesAction;
use Domain\EvtEvents\Models\Discipline;
use Domain\EvtEvents\Models\DisciplineTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class DuplicateDisciplinesCommand extends Command
{
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'evt:duplicate-disciplines
                            {--template-id= : The ID of the discipline template to duplicate disciplines from}
                            {--discipline-ids=* : The IDs of specific disciplines to duplicate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Duplicate multiple disciplines in bulk';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(BulkDuplicateDisciplinesAction $action)
    {
        $templateId = $this->option('template-id');
        $disciplineIds = $this->option('discipline-ids');

        if (! $templateId && empty($disciplineIds)) {
            $this->error('Either template-id or discipline-ids option is required.');

            return 1;
        }

        try {
            // Get disciplines to duplicate
            $disciplines = $this->getDisciplinesToDuplicate($templateId, $disciplineIds);

            if ($disciplines->isEmpty()) {
                $this->warn('No disciplines to duplicate.');

                return 0;
            }

            // Display disciplines to be duplicated
            $this->displayDisciplinesToDuplicate($disciplines);

            // Ask for confirmation
            if (! $this->confirm('Do you want to proceed with duplication?')) {
                $this->info('Operation cancelled.');

                return 0;
            }

            // Duplicate disciplines
            $newDisciplines = $this->duplicateDisciplines($action, $templateId, $disciplines);

            // Show results
            $this->displayDuplicationResults($disciplines, $newDisciplines);

            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());

            return 1;
        }
    }

    /**
     * Get the disciplines to duplicate.
     *
     * @param  int|null  $templateId
     */
    protected function getDisciplinesToDuplicate($templateId, array $disciplineIds): Collection
    {
        if ($templateId) {
            $template = DisciplineTemplate::with('disciplines.sport')->findOrFail($templateId);
            $this->info("Found template: {$template->name}");

            return $template->disciplines;
        } else {
            $disciplines = Discipline::with('sport')->whereIn('id', $disciplineIds)->get();
            if ($disciplines->count() !== count($disciplineIds)) {
                throw new \Exception('Some discipline IDs were not found.');
            }

            return $disciplines;
        }
    }

    /**
     * Display the disciplines to be duplicated.
     */
    protected function displayDisciplinesToDuplicate(Collection $disciplines): void
    {
        $this->info('The following disciplines will be duplicated:');
        $this->table(
            ['ID', 'Name', 'Sport', 'Gender', 'Enrollment Type'],
            $disciplines->map(function ($discipline) {
                return [
                    $discipline->id,
                    $discipline->name,
                    $discipline->sport->name ?? 'N/A',
                    $discipline->gender,
                    $discipline->enrollment_type,
                ];
            })
        );
    }

    /**
     * Duplicate the disciplines.
     *
     * @param  int|null  $templateId
     */
    protected function duplicateDisciplines(
        BulkDuplicateDisciplinesAction $action,
        $templateId,
        Collection $disciplines
    ): Collection {
        $this->info('Duplicating disciplines...');
        $progressBar = $this->output->createProgressBar($disciplines->count());
        $progressBar->start();

        $newDisciplines = $templateId
            ? $action->executeFromTemplate($templateId, function () use ($progressBar) {
                $progressBar->advance();
            })
            : $action->execute($disciplines, function () use ($progressBar) {
                $progressBar->advance();
            });

        $progressBar->finish();
        $this->newLine(2);

        return $newDisciplines;
    }

    /**
     * Display the results of the duplication.
     */
    protected function displayDuplicationResults(
        Collection $originalDisciplines,
        Collection $newDisciplines
    ): void {
        $this->info('Duplication completed successfully.');
        $this->table(
            ['Original ID', 'Original Name', 'New ID', 'New Name'],
            $originalDisciplines->zip($newDisciplines)->map(function ($pair) {
                return [
                    $pair[0]->id,
                    $pair[0]->name,
                    $pair[1]->id,
                    $pair[1]->name,
                ];
            })
        );
    }
}
