<?php

namespace Domain\DivingLogs\Actions;

use App\Notifications\RequestDivingLogValidationNotification;
use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogValidation;
use Domain\DivingLogs\States\PendingDivingLogValidationState;
use Domain\Entities\Models\Entity;
use Domain\Individuals\Actions\DetectIfIndividualIsInstructorAction;
use Domain\Individuals\Models\Individual;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestDivingLogValidationAction
{
    public function __construct(private DetectIfIndividualIsInstructorAction $detectInstructor) {}

    /**
     * @throws Exception
     */
    public function __invoke(int $divingLogId, string $CmasCode): DivingLogValidation
    {
        $divingLog = DivingLog::findOrFail($divingLogId);

        $individual = Individual::where('code_cmas', $CmasCode)->first();
        $entity = Entity::where('code_cmas', $CmasCode)->first();

        if (! $individual && ! $entity) {
            throw new Exception('Invalid CMAS code');
        }

        if ($individual) {
            return $this->handleIndividualValidator($divingLog, $individual);
        } else {
            return $this->handleEntityValidator($divingLog, $entity);
        }
    }

    private function handleIndividualValidator(DivingLog $divingLog, Individual $individual): DivingLogValidation
    {
        if ($divingLog->individual_id === $individual->id) {
            throw new Exception('You can\'t validate your own dive');
        }

        // Use a query builder instead of the model instance
        $isInstructor = ($this->detectInstructor)(Individual::where('id', $individual->id));
        if (! $isInstructor) {
            throw new Exception('The individual isn\'t an instructor', 500);
        }

        return $this->createValidation($divingLog, $individual);
    }

    private function handleEntityValidator(DivingLog $divingLog, Entity $entity): DivingLogValidation
    {
        // Entities can always validate, so we don't need additional checks here
        return $this->createValidation($divingLog, $entity);
    }

    private function createValidation(DivingLog $divingLog, $validator): DivingLogValidation
    {
        if (DivingLogValidation::where('diving_log_id', $divingLog->id)->exists()) {
            throw new Exception('The diving log request was already sent', 500);
        }

        try {
            DB::beginTransaction();
            $validation = new DivingLogValidation;
            $validation->diving_log_id = $divingLog->id;
            $validation->validator()->associate($validator);
            $validation->status_class = PendingDivingLogValidationState::class;
            $validation->save();
            $divingLog->setStateAttribute('pending');
            $divingLog->save();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            throw new Exception('The diving log request wasn\'t validated', 500);
        }

        // Send Notification to validator
        try {
            $notifiable = null;
            if ($validator instanceof Individual) {
                $notifiable = $validator->user; // Assuming 'user' is the relationship name
            } elseif ($validator instanceof Entity) {
                $notifiable = $validator->users()->first(); // Assuming 'users' is the relationship name
            }

            if ($notifiable) {
                $notifiable->notify(new RequestDivingLogValidationNotification($divingLog, $validator));
            } else {
                // Log a warning if no notifiable user is found
                Log::warning("Could not find a user to notify for validation request of DivingLog ID: {$divingLog->id} with Validator Type: " . get_class($validator) . " and ID: {$validator->id}");
            }
        } catch (Exception $ex) {
            // Log the notification error, but don't re-throw to let the validation creation succeed
            Log::error("Error sending validation request notification for DivingLog ID {$divingLog->id}: " . $ex->getMessage());
        }

        return $validation;
    }
}
