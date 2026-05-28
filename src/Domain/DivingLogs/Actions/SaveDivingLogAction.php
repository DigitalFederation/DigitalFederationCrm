<?php

namespace Domain\DivingLogs\Actions;

use App\Enums\DivingLogDiveTypeEnum;
use Domain\DivingLogs\DataTransferObject\DivingLogData;
use Domain\DivingLogs\DataTransferObject\DivingLogDivingData;
use Domain\DivingLogs\DataTransferObject\DivingLogExtendedRangeData;
use Domain\DivingLogs\DataTransferObject\DivingLogFreedivingData;
use Domain\DivingLogs\DataTransferObject\DivingLogRebreatherCcrData;
use Domain\DivingLogs\DataTransferObject\DivingLogRebreatherScrData;
use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogIndividualSequence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class SaveDivingLogAction
{
    /**
     * @throws \Exception
     */
    public function __invoke(array $divingLogArray, ?int $existing_record_id = null): DivingLog
    {
        try {
            DB::beginTransaction();

            // Convert to DTO for data integrity and validation purposes
            Log::info('SaveDivingLogAction: Raw diving_location_id received', ['id' => $divingLogArray['diving_location_id'] ?? 'not set']);
            $divingLogData = DivingLogData::fromArray($divingLogArray);
            Log::info('SaveDivingLogAction: DTO diving_location_id after fromArray', ['id' => $divingLogData->diving_location_id ?? 'not set']);
            Log::info('SaveDivingLogAction: DTO location object ID after fromArray', ['location_id' => $divingLogData->location?->id ?? 'not set']);

            // Save or update data to DivingLog table
            if ($existing_record_id !== null) {
                // Update the existing record
                $divingLog = DivingLog::findOrFail($existing_record_id);
                $dataToUpdate = $divingLogData->toArray();
                Log::info('SaveDivingLogAction: Array passed to update() for existing record', $dataToUpdate);
                $result = $divingLog->update($dataToUpdate);
                Log::info('SaveDivingLogAction: Model attributes after update() call', $divingLog->getAttributes());
            } else {
                // Create a new record
                $dataToCreate = $divingLogData->toArray();
                Log::info('SaveDivingLogAction: Array passed to create() for new record', $dataToCreate);
                $divingLog = DivingLog::create($dataToCreate);
                Log::info('SaveDivingLogAction: Model attributes after create() call', $divingLog->getAttributes());

                // Handle sequence number before incrementing
                if (isset($divingLogArray['dive_sequence_number'])) {
                    $initialSequence = DivingLogIndividualSequence::create([
                        'individual_id' => $divingLog->individual_id,
                        'diving_log_id' => $divingLog->id,
                        'dive_type' => $divingLog->dive_type,
                        'log_number' => (int) $divingLogArray['dive_sequence_number'],
                        'initial_log_number' => (int) $divingLogArray['dive_sequence_number'],
                    ]);
                }
            }

            // Save relationships to other tables
            switch ($divingLog->dive_type) {
                case DivingLogDiveTypeEnum::Freediving:
                    if ($divingLogData->freedivingData !== null) {
                        $divingLog->load('freediving');
                        $freedivingModel = DivingLogFreedivingData::toModel(DivingLogFreedivingData::fromArray($divingLogData->freedivingData), $divingLog->freediving?->id);
                        $freedivingModel->diving_log_id = $divingLog->id;
                        $freedivingModel->save();
                    }
                    break;
                case DivingLogDiveTypeEnum::Diving:
                    if ($divingLogData->divingData !== null) {
                        $divingLog->load('diving');
                        $divingModel = DivingLogDivingData::toModel(DivingLogDivingData::fromArray($divingLogData->divingData), $divingLog->diving?->id);
                        $divingModel->diving_log_id = $divingLog->id;
                        $divingModel->save();
                    }
                    break;
                case DivingLogDiveTypeEnum::ExtendedRange:
                    if ($divingLogData->extendedRangeData !== null) {
                        $divingLog->load('extendedRange');
                        $extendedRangeModel = DivingLogExtendedRangeData::toModel(DivingLogExtendedRangeData::fromArray($divingLogData->extendedRangeData), $divingLog->extendedRange?->id);
                        $extendedRangeModel->diving_log_id = $divingLog->id;
                        $extendedRangeModel->save();
                    }
                    break;
                case DivingLogDiveTypeEnum::RebreatherCcr:
                    if ($divingLogData->rebreatherCCRData !== null) {
                        $divingLog->load('rebreatherCCR');
                        $rebreatherCCRModel = DivingLogRebreatherCCRData::toModel(DivingLogRebreatherCCRData::fromArray($divingLogData->rebreatherCCRData), $divingLog->rebreatherCCR?->id);
                        $rebreatherCCRModel->diving_log_id = $divingLog->id;
                        $rebreatherCCRModel->save();
                    }
                    break;
                case DivingLogDiveTypeEnum::RebreatherScr:
                    if ($divingLogData->rebreatherSCRData !== null) {
                        $divingLog->load('rebreatherSCR');
                        $rebreatherSCRModel = DivingLogRebreatherSCRData::toModel(DivingLogRebreatherSCRData::fromArray($divingLogData->rebreatherSCRData), $divingLog->rebreatherSCR?->id);
                        $rebreatherSCRModel->diving_log_id = $divingLog->id;
                        $rebreatherSCRModel->save();
                    }
                    break;
            }
            DB::commit();

            // Only increment if it's not the first dive with a custom sequence
            if ($existing_record_id === null && ! isset($divingLogArray['dive_sequence_number'])) {
                $incrementAction = new IncrementDiveLogSequenceAction;
                $incrementAction->execute($divingLog);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            throw new \Exception('Error saving diving log');
        }

        return $divingLog;
    }
}
