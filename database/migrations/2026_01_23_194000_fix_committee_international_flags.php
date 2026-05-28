<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ensure committee is_international flags are set correctly:
     * - SPORT: is_international = false (national sports)
     * - SCIENTIFIC: is_international = true (CMAS international)
     * - DIVING: is_international = true (CMAS international)
     * - DIVINGSERVICES: is_international = false (national diving services)
     *
     * This is critical for the license purchase flow:
     * - International committees (DIVING, SCIENTIFIC): No TD approval required
     * - Non-international diving (DIVINGSERVICES): TD approval required for entities
     */
    public function up(): void
    {
        $corrections = [
            'SPORT' => false,
            'SCIENTIFIC' => true,
            'DIVING' => true,
            'DIVINGSERVICES' => false,
        ];

        foreach ($corrections as $code => $shouldBeInternational) {
            $committee = DB::table('committee')->where('code', $code)->first();

            if (! $committee) {
                Log::warning("Migration: Committee {$code} not found");

                continue;
            }

            $currentValue = (bool) $committee->is_international;

            if ($currentValue !== $shouldBeInternational) {
                DB::table('committee')
                    ->where('code', $code)
                    ->update(['is_international' => $shouldBeInternational]);

                Log::info("Migration: Fixed {$code} committee is_international flag", [
                    'from' => $currentValue,
                    'to' => $shouldBeInternational,
                ]);
            } else {
                Log::info("Migration: {$code} committee is_international flag already correct ({$shouldBeInternational})");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Log::warning('Migration rollback: Not reverting committee flags as this could break license flow');
    }
};
