<?php

namespace App\Http\Controllers\Entity;

use App\Enums\DivingLogDiveTypeEnum;
use App\Http\Controllers\Controller;
use App\Notifications\ValidateDivingLogNotification;
use Domain\DivingLogs\Actions\IncrementDiveLogSequenceAction;
use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogValidation;
use Domain\DivingLogs\States\PendingDivingLogState;
use Domain\DivingLogs\States\PendingDivingLogValidationState;
use Domain\Entities\Models\Entity;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DivingLogValidationController extends Controller
{
    /**
     * Display a listing of diving logs pending validation for the current entity.
     */
    public function index(): View
    {
        $entity = auth()->user()->entities()->first();

        if (! $entity) {
            // Handle case where the user is not associated with any entity
            // You might want to redirect or show an error message.
            abort(403, 'User is not associated with an entity.');
        }

        // Fetch pending validation requests specifically for this entity.
        $pendingValidations = DivingLogValidation::query()
            ->where('validator_type', Entity::class)
            ->where('validator_id', $entity->id)
            ->where('status_class', PendingDivingLogValidationState::class)
            ->with(['divingLog.individual', 'divingLog.location']) // Eager load log and related data
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Extract the DivingLog models from the paginated validation results
        $pendingDivingLogs = $pendingValidations->through(fn ($validation) => $validation->divingLog);

        $diveTypes = DivingLogDiveTypeEnum::cases();

        // Pass the paginated diving logs to the view
        // Note: The view should now expect a LengthAwarePaginator instance containing DivingLog models.
        return view('web.entity.diving_log_validation.index', compact('pendingDivingLogs', 'diveTypes', 'pendingValidations'));
    }

    public function show(int $id): View|RedirectResponse
    {
        $entity = auth()->user()->entities()->first();

        // Fetch the DivingLog directly by ID, including necessary relationships.
        // Remove the restriction based on individual's association with the entity.
        $divingLog = DivingLog::with(['individual', 'location', 'validation', 'buddies'])
            ->find($id);

        if (! $divingLog) {
            // Log not found, redirect back with an error.
            return redirect()->route('entity.diving-log-validation.index')
                ->with('error', 'Diving log not found.');
        }

        // Determine if the log can be approved (must be in 'pending' state)
        $canApprove = $divingLog->stateName() === 'pending';

        // Load the specific dive type data (e.g., free_diving, scuba_diving)
        $divingLog->loadDiveTypeRelation();

        return view('web.entity.diving_log_validation.show', compact('divingLog', 'canApprove'));
    }

    /**
     * Validate a pending diving log.
     *
     * Any authenticated entity can validate any log in a pending state.
     */
    public function update(int $divingLogId): RedirectResponse
    {
        try {
            // Eager load necessary relationships
            $divingLog = DivingLog::with('individual.user')->findOrFail($divingLogId);
            $entity = auth()->user()->entities()->first();

            if (! $entity) {
                throw new Exception('Authenticated user is not associated with an entity.', 403);
            }

            DB::transaction(function () use ($divingLog, $entity) {
                // Ensure the diving log is pending validation
                if ($divingLog->status_class !== PendingDivingLogState::class) {
                    throw new Exception('The diving log is not pending validation.', 422); // 422 Unprocessable Entity
                }

                // Update diving log state to approved
                $divingLog->setStateAttribute('approved');
                $divingLog->save();

                // Record the validation action, linking it to the entity
                DivingLogValidation::updateOrCreate(
                    ['diving_log_id' => $divingLog->id], // Find by log ID (assumes only one validation record per log)
                    [
                        'validator_id' => $entity->id,
                        'validator_type' => $entity->getMorphClass(),
                        'validated_at' => Carbon::now(),
                    ]
                );

                // Increment the dive log sequence number for the individual.
                // Ensure this action correctly handles the sequence logic based on the log's individual.
                $incrementDiveLogSequence = new IncrementDiveLogSequenceAction;
                $incrementDiveLogSequence->execute($divingLog);
            });

            // Send Notification to the log owner
            try {
                // Ensure the user relationship is loaded for notification
                $divingLog->individual->user->notify(new ValidateDivingLogNotification($divingLog));
            } catch (Exception $ex) {
                // Log the notification error but don't fail the whole validation
                Log::error('Failed to send validation notification for DivingLog ID ' . $divingLog->id . ': ' . $ex->getMessage());

                // Optionally, redirect with a specific warning message about the notification failure
                return redirect()->back()->with('warning', 'Validation completed, but failed to send notification to the diver.');
            }
        } catch (Exception $ex) {
            Log::error('Diving Log Validation Error: ' . $ex->getMessage());

            // Provide more specific feedback based on exception type if possible
            $statusCode = is_int($ex->getCode()) && $ex->getCode() >= 400 && $ex->getCode() < 600 ? $ex->getCode() : 500;
            $errorMessage = $statusCode === 500 ? 'An unexpected error occurred during validation.' : $ex->getMessage();

            return redirect()->back()->with('error', $errorMessage);
        }

        return redirect()->back()->with('success', 'Diving log validation completed successfully.');
    }

    public function approvedDives(): View
    {
        $entity = auth()->user()->entities()->first();

        // Fetch validation records created by the current entity where validated_at is set
        $validationsByThisEntity = DivingLogValidation::query()
            ->where('validator_type', $entity->getMorphClass()) // Ensure correct validator type
            ->where('validator_id', $entity->id)       // Filter by the current entity's ID
            ->whereNotNull('validated_at')             // Ensure the dive was actually validated
            // Eager load the associated DivingLog details needed for the view
            ->with(['divingLog.individual', 'divingLog.location'])
            // Optionally, ensure the related log is still marked as approved (for data integrity)
            ->whereHas('divingLog', function (Builder $query) {
                $query->where('status_class', \Domain\DivingLogs\States\ApprovedDivingLogState::class);
            })
            ->orderBy('validated_at', 'desc') // Order by when they were validated
            ->paginate(15); // Use pagination

        $diveTypes = DivingLogDiveTypeEnum::cases(); // Keep if needed for filtering

        // Pass the collection of this entity's validation records to the view
        return view('web.entity.diving_log_validation.approved-dives', compact('validationsByThisEntity', 'diveTypes'));
    }
}
