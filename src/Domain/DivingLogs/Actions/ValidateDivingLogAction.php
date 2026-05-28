<?php

namespace Domain\DivingLogs\Actions;

use App\Models\Scopes\DivingLogSearchScope;
use App\Notifications\ValidateDivingLogNotification;
use Carbon\Carbon;
use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\States\ApprovedDivingLogValidationState;
use Domain\DivingLogs\States\PendingDivingLogState;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ValidateDivingLogAction
{
    /**
     * @throws Exception
     */
    public function __invoke(int $divingLogId): DivingLog|Model
    {
        try {
            $divingLog = DivingLog::with('individual.user', 'validation.validator')
                ->withoutGlobalScope(DivingLogSearchScope::class)
                ->findOrFail($divingLogId);

            DB::transaction(function () use ($divingLog) {
                $this->validateAuthorization($divingLog);
                $this->updateDivingLogStatus($divingLog);
                $this->updateValidationStatus($divingLog);
            });

            $this->notifyDiveLogOwner($divingLog);

            Log::info("Diving log {$divingLogId} successfully validated by user " . auth()->id());

            return $divingLog;
        } catch (\Exception $e) {
            Log::error("Error validating diving log {$divingLogId}: " . $e->getMessage());
            throw new Exception('Failed to validate diving log: ' . $e->getMessage());
        }
    }

    private function validateAuthorization(DivingLog $divingLog): void
    {
        /** @var \App\Models\User|null $authenticatedUser */
        $authenticatedUser = auth()->user();

        if (! $authenticatedUser) {
            throw new Exception('User not authenticated.');
        }

        $isAssociatedWithEntity = $authenticatedUser->entities()->exists();

        if (! $isAssociatedWithEntity) {
            throw new Exception('User is not associated with an Entity and cannot validate this diving log.');
        }
    }

    private function updateDivingLogStatus(DivingLog $divingLog): void
    {
        if ($divingLog->status_class !== PendingDivingLogState::class) {
            throw new Exception('The diving log is not pending validation');
        }

        $divingLog->setStateAttribute('approved');
        $divingLog->save();
    }

    private function updateValidationStatus(DivingLog $divingLog): void
    {
        $validation = $divingLog->validation->first();
        $validation->validated_at = Carbon::now();
        $validation->status_class = ApprovedDivingLogValidationState::class;
        $validation->save();
    }

    private function notifyDiveLogOwner(DivingLog $divingLog): void
    {
        try {
            $divingLog->individual->user->notify(new ValidateDivingLogNotification($divingLog));
        } catch (\Exception $e) {
            Log::warning("Failed to send notification for validated diving log {$divingLog->id}: " . $e->getMessage());
        }
    }
}
