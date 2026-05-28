<?php

namespace App\Console\Commands;

use Domain\Licenses\Models\LicenseAttributed;
use Illuminate\Console\Command;

class RepairLicenseEndDatesCommand extends Command
{
    protected $signature = 'license:repair-end-dates';
    protected $description = 'Repair null end dates for licenses based on their creation year';

    public function handle()
    {
        $this->info('Starting license end dates repair...');

        // Handle 2024 licenses
        $count2024 = LicenseAttributed::whereNull('current_term_ends_at')
            ->whereYear('created_at', '2024')
            ->update([
                'current_term_ends_at' => '2024-12-31',
            ]);

        $this->info("Fixed {$count2024} licenses from 2024");

        // Handle 2025 licenses
        $count2025 = LicenseAttributed::whereNull('current_term_ends_at')
            ->whereYear('created_at', '2025')
            ->update([
                'current_term_ends_at' => '2025-12-31',
            ]);

        $this->info("Fixed {$count2025} licenses from 2025");

        // Report any remaining nulls (created before 2024)
        $remainingNulls = LicenseAttributed::whereNull('current_term_ends_at')
            ->whereYear('created_at', '<', '2024')
            ->count();

        if ($remainingNulls > 0) {
            $this->warn("Found {$remainingNulls} licenses created before 2024 with null end dates.");
            $this->warn('Please review these manually as they might need special attention.');
        }

        $this->info('End date repair completed!');
    }
}
