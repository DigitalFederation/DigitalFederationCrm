<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Domain\EvtEvents\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EventsApiController extends Controller
{
    /**
     * Fetch competition events between start and end dates
     */
    public function getCompetitionEvents(Request $request): AnonymousResourceCollection
    {
        try {
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'competition_type' => [
                    'nullable',
                    'string',
                    Rule::in(['world_championship', 'continental_championship', 'world_cup', 'other']),
                ],
            ]);
        } catch (ValidationException $e) {
            // Ensure validation errors return 422 status code in API context
            throw new ValidationException(
                $e->validator,
                response()->json(['errors' => $e->errors()], 422)
            );
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $competitionType = $request->input('competition_type');

        // Cache the results for 15 minutes

        $query = Event::query()
            ->where('event_category', 'competition')
            ->where('is_visible', true);

        // Apply date filters if provided
        if ($startDate && $endDate) {
            $query->where(function ($query) use ($startDate, $endDate) {
                // Get events that overlap with the provided date range
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    // Also include events that completely span the requested range
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });
        }

        // Load relationships for better performance
        $query->with([
            'competition',
            'competition.types',
            'competition.sport',
            'venueCountry',
        ]);

        // Filter by competition type if provided
        if ($competitionType) {
            $query->whereHas('competition.types', function ($q) use ($competitionType) {
                $q->where('competition_type', $competitionType);
            });
        }

        // Get the results ordered by start date
        $events = $query->orderBy('start_date', 'asc')->get();

        return EventResource::collection($events);
    }
}
