<?php

namespace App\Console\Commands;

use Domain\Individuals\Models\Individual;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class PopulateLatinNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-latin-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate first_name_latin and last_name_latin for existing individuals based on name and surname.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting population of Latin names for individuals...');

        $totalUpdated = 0;
        $chunkSize = 200; // Process in chunks to manage memory

        try {
            // Use chunkById for efficiency, especially on large tables
            Individual::query()
                ->orderBy('id') // chunkById requires an ordered column
                ->chunkById($chunkSize, function (Collection $individuals) use (&$totalUpdated): bool {
                    $updates = [];
                    foreach ($individuals as $individual) {
                        // Use the same cleaning and transliteration logic as the mutators
                        $cleanedFirstName = preg_replace('/[^\p{L}\p{N}\'\s-]/u', '', $individual->name ?? '');

                        $latinFirstName = Str::ascii($cleanedFirstName);

                        $cleanedLastName = preg_replace('/[^\p{L}\p{N}\'\s-]/u', '', $individual->surname ?? '');
                        $latinLastName = Str::ascii($cleanedLastName);

                        // Only update if the latin version is different or null
                        if ($individual->first_name_latin !== $latinFirstName || $individual->last_name_latin !== $latinLastName) {
                            // Prepare data for update
                            $updates[$individual->id] = [
                                'first_name_latin' => $latinFirstName,
                                'last_name_latin' => $latinLastName,
                            ];
                        }
                    }

                    if (! empty($updates)) {
                        try {
                            // Use a transaction for the updates in this chunk
                            DB::transaction(function () use ($updates) {
                                foreach ($updates as $id => $data) {
                                    // Update using Query Builder for efficiency, bypassing model events
                                    DB::table('individual')->where('id', $id)->update($data);
                                }
                            });
                            $updatedCount = count($updates);
                            $totalUpdated += $updatedCount;
                            $this->info($updatedCount . ' records updated in this chunk.');
                        } catch (Throwable $e) {
                            $this->error('Error updating chunk: ' . $e->getMessage());

                            // Stop processing further chunks on error
                            return false;
                        }
                    } else {
                        $this->info('No records needed updating in this chunk (' . count($individuals) . ' checked).');
                    }

                    // Optional: Add a small delay if needed to reduce DB load
                    // usleep(100000); // 100ms

                    return true; // Continue processing next chunk

                }, 'id'); // Specify the column name for chunkById

        } catch (Throwable $e) {
            $this->error('An unexpected error occurred during population: ' . $e->getMessage());

            return Command::FAILURE;
        }

        $this->info("Population complete. Total records updated: {$totalUpdated}.");

        return Command::SUCCESS;
    }
}
