<?php

namespace Domain\DivingLogs\Actions;

use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogIndividualSequence;
use Exception;
use Illuminate\Support\Facades\DB;

class IncrementDiveLogSequenceAction
{
    /**
     * Increment the dive log sequence for an individual.
     *
     * @throws Exception
     */
    public function execute(DivingLog $divingLog): void
    {

        try {
            DB::beginTransaction();

            // Get the latest sequence for this individual and dive type
            $latestSequence = DivingLogIndividualSequence::lockForUpdate()
                ->where([
                    'individual_id' => $divingLog->individual_id,
                    'dive_type' => $divingLog->dive_type,
                ])
                ->orderBy('log_number', 'desc')
                ->first();

            // Calculate the next sequence number
            $nextSequenceNumber = $latestSequence ? $latestSequence->log_number + 1 : 1;

            // Create new sequence entry
            DivingLogIndividualSequence::create([
                'individual_id' => $divingLog->individual_id,
                'diving_log_id' => $divingLog->id,
                'dive_type' => $divingLog->dive_type,
                'log_number' => $nextSequenceNumber,
                'initial_log_number' => $latestSequence?->initial_log_number,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
