<?php

namespace App\Http\Controllers\Individual;

use App\Enums\DivingLogDiveTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDivingLogValidationRequest;
use App\Models\Scopes\DivingLogSearchScope;
use Domain\DivingLogs\Actions\IncrementDiveLogSequenceAction;
use Domain\DivingLogs\Actions\RequestDivingLogValidationAction;
use Domain\DivingLogs\Actions\ValidateDivingLogAction;
use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogValidation;
use Domain\DivingLogs\States\PendingDivingLogValidationState;
use Domain\Individuals\Models\Individual;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DivingLogValidationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->can('view-instructor-menu') || auth()->user()->can('access instructor menu')) {

            $instructor = auth()->user()->individuals()->first();

            $validationRequests = DivingLogValidation::with(['divingLog' => function ($query) {
                $query->withoutGlobalScope(DivingLogSearchScope::class);
            }])
                ->where('validator_id', $instructor->id)
                ->where('validator_type', Individual::class)
                ->where('status_class', PendingDivingLogValidationState::class)
                ->whereNull('validated_at')
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $divingLogsByYear = $validationRequests->pluck('divingLog')->groupBy(function ($item) {
                return date('Y', strtotime($item->date_and_time));
            });

            $diveTypes = DivingLogDiveTypeEnum::cases();

            return view('web.individual.diving_log_validation.index', compact('validationRequests', 'divingLogsByYear', 'diveTypes'));
        } else {
            abort(403);
        }
    }

    public function show(int $id): View|RedirectResponse
    {
        $instructor = auth()->user()->individuals()->first();

        $divingLog = DivingLog::with(['individual', 'location', 'validation', 'buddies'])
            ->whereHas('validation', function ($query) use ($instructor) {
                $query->where('validator_id', $instructor->id)
                    ->where('validator_type', Individual::class);
            })
            ->withoutGlobalScope(DivingLogSearchScope::class)
            ->find($id);

        if (! $divingLog) {
            return redirect()->route('individual.diving-log-validation.index')
                ->with('error', 'Diving log not found or not associated with your validation requests.');
        }

        $canApprove = $divingLog->stateName() === 'pending';

        // Load the specific dive type data
        $divingLog->loadDiveTypeRelation();

        return view('web.individual.diving_log_validation.show', compact('divingLog', 'canApprove'));
    }

    public function create(int $divingLogId): View
    {
        $divingLog = DivingLog::with('validation')
            ->where('id', $divingLogId)
            ->whereIn('individual_id', auth()->user()->individuals()->pluck('id'))
            ->firstOrFail();

        // Check if the diving log has already been validated
        $isValidated = $divingLog->validation->where('validated_at', '!=', null)->count() > 0;

        if ($isValidated) {
            abort(403, 'This diving log has already been validated and cannot be validated again.');
        }

        return view('web.individual.diving_log_validation.create', compact('divingLogId'));
    }

    public function store(
        int $divingLogId,
        StoreDivingLogValidationRequest $request,
        RequestDivingLogValidationAction $action): RedirectResponse
    {
        // Check if the diving log has already been validated
        $divingLog = DivingLog::with('validation')->findOrFail($divingLogId);
        $isValidated = $divingLog->validation->where('validated_at', '!=', null)->count() > 0;

        if ($isValidated) {
            return redirect()->back()->with('error', 'This diving log has already been validated and cannot be validated again.');
        }

        try {
            $action($divingLogId, $request->cmas_code);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return redirect()->back()->with('error', $ex->getMessage())->withInput();
        }

        return redirect()->back()->with('success', 'Validation request sent.');
    }

    public function edit(int $divingLogId): View
    {
        if (DivingLog::where('id', $divingLogId)
            ->whereHas('validation', function (Builder $query) {
                $query->whereIn('individual_id', auth()->user()->individuals()->pluck('id'));
            })->exists()
        ) {
            $divingLog = DivingLog::findOrFail($divingLogId);
            $canApprove = true;

            return view('web.individual.diving_log.show', compact('divingLog', 'canApprove'));
        } else {
            abort(403);
        }
    }

    public function update(
        int $divingLogId,
        ValidateDivingLogAction $validateDiveLogAction,
        IncrementDiveLogSequenceAction $incrementAction): RedirectResponse
    {

        try {
            DB::beginTransaction();
            $divingLog = $validateDiveLogAction($divingLogId);

            // Increment the dive log sequence number.
            $incrementAction->execute($divingLog);
            DB::commit();
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            DB::rollBack();

            return redirect()->back()->with('error', $ex->getMessage());
        }

        return redirect()->back()->with('success', 'Validation completed.');
    }
}
