<?php

declare(strict_types=1);

namespace Domain\DivingLogs\Actions;

use Carbon\Carbon;
use Domain\DivingLogs\Models\DivingCourse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ListAvailableDivingCoursesAction
{
    /**
     * Retrieves available diving courses for a specific diving location,
     * optionally filtered by an entity.
     *
     * Available courses are those with a null start_date or a start_date in the future.
     * Eager loads necessary relationships for display.
     */
    public function __invoke(int $divingLocationId, ?int $entityId = null): Collection
    {
        return DivingCourse::query()
            ->where('diving_location_id', $divingLocationId)
            ->when($entityId, function (Builder $query, int $id) {
                $query->where('entity_id', $id);
            })
            ->where(function (Builder $query) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '>=', Carbon::today());
            })
            ->with([
                'certification:id,name,description,minimum_age,confined_water_sessions,open_water_sessions,theoretical_sessions', // Eager load necessary fields
                'entity:id,name', // Optional: Load entity name if needed
            ])
            ->orderBy('start_date') // Optional: Order by start date
            ->get();
    }
}
