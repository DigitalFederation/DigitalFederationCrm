<?php

namespace App\Http\Controllers\Individual;

use App\Enums\DivingLogCategoryEnum;
use App\Enums\DivingLogDiveTypeEnum;
use App\Http\Controllers\Controller;
use Domain\DivingLogs\Models\DivingLocation;
use Domain\DivingLogs\Models\DivingLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DivingLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        $divingLogs = QueryBuilder::for(DivingLog::class)
            ->allowedFilters([
                AllowedFilter::scope('filter_type'),
                AllowedFilter::scope('filter_category'),
            ])
            ->with(['location', 'sequence'])
            ->where('individual_id', Auth::user()->individuals()->first()->id)
            ->orderByDesc('date_and_time')
            ->paginate(15);

        $divingLogsByYear = $divingLogs->groupBy(function (DivingLog $item) {
            return date('Y', strtotime($item->date_and_time));
        });

        $diveTypes = DivingLogDiveTypeEnum::toArray();
        $diveCategories = DivingLogCategoryEnum::toArray();

        return view('web.individual.diving_log.index', compact('divingLogs', 'diveTypes', 'diveCategories', 'divingLogsByYear'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $individualId = Auth::user()->individuals()->first()->id;
        $hasDiveLogs = DivingLog::where('individual_id', $individualId)->exists();

        if (! $hasDiveLogs) {
            // This is the user's first dive log
            return view('web.individual.diving_log.create-first');
        }

        return view('web.individual.diving_log.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $divingLog = DivingLog::with('location', 'validation', 'buddies', 'sequence')
            ->findOrFail($id);

        // Check if the diving log has been validated (has validation records with validated_at not null)
        $isValidated = $divingLog->validation->where('validated_at', '!=', null)->count() > 0;

        return view('web.individual.diving_log.show', [
            'divingLog' => $divingLog,
            'isValidated' => $isValidated,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DivingLog $divingLog): View
    {
        $divingLog->load('location', 'diving', 'extendedRange', 'freeDiving', 'rebreatherCCR', 'rebreatherSCR');
        // Ensure location data is always available
        $location = $divingLog->location ?? new DivingLocation;

        return view('web.individual.diving_log.edit', compact('divingLog', 'location'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DivingLog $divingLog): RedirectResponse
    {

        // Logged in Individual Id
        $individualId = Auth::user()->individuals()->first()->id;

        // Check if the individual is the owner of the record
        if ($divingLog->individual_id !== $individualId) {
            return redirect()->route('individual.diving-log.index')
                ->with('error', 'You are not authorized to delete this diving log.');
        }

        // Use a database transaction to ensure atomicity
        DB::transaction(function () use ($divingLog) {
            // Delete related validation records
            $divingLog->validation()->delete();

            // Detach related buddies
            $divingLog->buddies()->detach();

            // Delete other related models
            $divingLog->diving()->delete();
            $divingLog->extendedRange()->delete();
            $divingLog->freeDiving()->delete();
            $divingLog->rebreatherCCR()->delete();
            $divingLog->rebreatherSCR()->delete();

            // Finally, delete the diving log
            $divingLog->delete();
        });

        // Redirect back with a success message
        return redirect()->route('individual.diving-log.index')
            ->with('success', 'Diving log and associated records deleted successfully');
    }
}
