<?php

use Domain\Licenses\States\ActiveLicenseAttributedState;
use Domain\Licenses\States\CanceledLicenseAttributedState;
use Domain\Licenses\States\ExpiredLicenseAttributedState;
use Domain\Licenses\States\PendingLicenseAttributedState;
use Domain\Licenses\States\ProvisionalLicenseAttributedState;
use Domain\Licenses\States\WaitingApprovalLicenseAttributedState;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Map old states to new simplified states
        $stateMapping = [
            WaitingApprovalLicenseAttributedState::class => PendingLicenseAttributedState::class,
            ProvisionalLicenseAttributedState::class => ActiveLicenseAttributedState::class,
            CanceledLicenseAttributedState::class => ExpiredLicenseAttributedState::class,
        ];

        // Update existing license states
        foreach ($stateMapping as $oldState => $newState) {
            DB::table('license_attributed')
                ->where('status_class', $oldState)
                ->update(['status_class' => $newState]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible as we're simplifying states
        // Old state information would be lost
    }
};
