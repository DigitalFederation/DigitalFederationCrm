<?php

namespace App\Http\Controllers\Federation;

use App\Enums\DivingLogDiveTypeEnum;
use App\Http\Controllers\Controller;
use Domain\DivingLogs\Models\DivingLog;
use Illuminate\Contracts\View\View;

class DivingLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        $federationUserId = auth()->user()->federations()->first()->id;

        $divingLogs = DivingLog::whereHas('individual', function ($query) use ($federationUserId) {
            $query->filterFederation($federationUserId);
        })
            ->with(['individual', 'location', 'buddies', 'sequence'])
            ->orderByDesc('date_and_time')
            ->paginate(15);

        $divingLogsByYear = $divingLogs->groupBy(function ($item) {
            return date('Y', strtotime($item->date_and_time));
        });

        return view('web.federation.diving_log.index', [
            'divingLogs' => $divingLogs,
            'diveTypes' => DivingLogDiveTypeEnum::cases(),
            'divingLogsByYear' => $divingLogsByYear,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $divingLog = DivingLog::with('location', 'validation', 'buddies', 'sequence')
            ->findOrFail($id);

        return view('web.federation.diving_log.show', [
            'divingLog' => $divingLog,
        ]);
    }
}
