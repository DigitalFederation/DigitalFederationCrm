<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\Controller;
use App\Notifications\ValidateDivingLogNotification;
use Domain\DivingLogs\Actions\IncrementDiveLogSequenceAction;
use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogValidation;
use Domain\DivingLogs\States\PendingDivingLogState;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DivingLogValidationController extends Controller
{
    public function update(int $divingLogId): RedirectResponse
    {
        try {
            $divingLog = DivingLog::with('individual.user', 'validation')->findOrFail($divingLogId);

            DB::transaction(function () use ($divingLog) {
                // Validate diving log
                if ($divingLog->status_class == PendingDivingLogState::class) {
                    $divingLog->setStateAttribute('approved');
                    $divingLog->save();

                    $validation = $divingLog->validation->first();

                    if (! $validation) {
                        // Create a new validation record if one doesn't exist
                        $validation = new DivingLogValidation([
                            'diving_log_id' => $divingLog->id,
                            'individual_id' => $divingLog->individual_id,
                        ]);
                    }

                    $validation->validated_at = Carbon::now();
                    $validation->save();

                    // Increment the dive log sequence number.
                    $incrementDiveLogSequence = new IncrementDiveLogSequenceAction;
                    $incrementDiveLogSequence->execute($divingLog);
                } else {
                    throw new Exception('The diving log is\'t pending to be validated', 500);
                }
            });

            // Send Notification to owner
            try {
                $divingLog->individual->user->notify(new ValidateDivingLogNotification($divingLog));
            } catch (Exception $ex) {
                Log::error($ex);
                throw new Exception('The diving log was validated but an error occurred while sending notification', 500);
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return redirect()->back()->with('error', $ex->getMessage());
        }

        return redirect()->back()->with('success', 'Validation completed.');
    }
}
