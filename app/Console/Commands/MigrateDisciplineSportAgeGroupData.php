<?php

namespace App\Console\Commands;

use Domain\EvtEvents\Models\Discipline;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateDisciplineSportAgeGroupData extends Command
{
    protected $signature = 'data:migrate-discipline-sport-age-group';
    protected $description = 'Migrate existing discipline sport age group data to the new many-to-many relationship';

    public function handle()
    {
        $this->info('Starting migration of discipline sport age group data...');

        $disciplines = Discipline::whereNotNull('sport_age_group_id')->get();
        $count = 0;

        foreach ($disciplines as $discipline) {
            $exists = DB::table('evt_discipline_sport_age_groups')
                ->where('discipline_id', $discipline->id)
                ->where('sport_age_group_id', $discipline->sport_age_group_id)
                ->exists();

            if (! $exists) {
                DB::table('evt_discipline_sport_age_groups')->insert([
                    'discipline_id' => $discipline->id,
                    'sport_age_group_id' => $discipline->sport_age_group_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $count++;
            }
        }

        $this->info("Migration completed. $count new relationships created.");

        if ($this->confirm('Do you want to remove the old sport_age_group_id column from the disciplines table?')) {
            if (Schema::hasColumn('evt_disciplines', 'sport_age_group_id')) {
                Schema::table('evt_disciplines', function (Blueprint $table) {
                    $table->dropForeign(['sport_age_group_id']);
                    $table->dropColumn('sport_age_group_id');
                });
                $this->info('Old column removed successfully.');
            } else {
                $this->info('Old column not found. No action taken.');
            }
        }
    }
}
