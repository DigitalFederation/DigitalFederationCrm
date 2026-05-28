<?php

namespace Database\Seeders;

use App\Models\Committee;
use Illuminate\Database\Seeder;

class CommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Committee Structure (see docs/committee_structure.md):
     * - SPORT: National underwater sports (is_international = false)
     * - DIVINGSERVICES: National diving services (is_international = false)
     * - DIVING: CMAS diving (is_international = true)
     * - SCIENTIFIC: CMAS scientific (is_international = true)
     */
    public function run(): void
    {
        $committees = [
            [
                'code' => 'SPORT',
                'name' => 'Sport Committee',
                'is_international' => false,
            ],
            [
                'code' => 'SCIENTIFIC',
                'name' => 'CMAS Scientific Committee',
                'is_international' => true,
            ],
            [
                'code' => 'DIVING',
                'name' => 'CMAS Diving Committee',
                'is_international' => true,
            ],
            [
                'code' => 'DIVINGSERVICES',
                'name' => 'Diving Services Committee',
                'is_international' => false,
            ],
        ];

        foreach ($committees as $committee) {
            Committee::updateOrCreate(
                ['code' => $committee['code']],
                $committee
            );
        }
    }
}
