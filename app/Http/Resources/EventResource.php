<?php

namespace App\Http\Resources;

use Domain\EvtEvents\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $event = $this->resource;
        assert($event instanceof Event);

        return [
            'id' => $event->id,
            'name' => $event->name,
            'event_type' => $event->event_type,
            'event_category' => $event->event_category,
            'location' => $event->location,
            'venue' => $event->venue,
            'venue_address' => $event->venue_address,
            'venue_city' => $event->venue_city,
            'venue_country' => $this->whenLoaded('venueCountry', function () {
                $event = $this->resource;
                assert($event instanceof Event);

                return $event->venueCountry ? [
                    'id' => $event->venueCountry->id,
                    'name' => $event->venueCountry->name,
                    'code' => $event->venueCountry->code,
                ] : null;
            }),
            'start_date' => $event->start_date?->format('Y-m-d'),
            'end_date' => $event->end_date?->format('Y-m-d'),
            'start_registration' => $event->start_registration?->format('Y-m-d'),
            'end_registration' => $event->end_registration?->format('Y-m-d'),
            'other_deadlines' => $event->other_deadlines,
            'featured_image' => $event->featured_image,
            'description' => $event->description,
            'external_url' => $event->external_url,
            'status' => $event->getStateLabel(),
            'is_visible' => $event->is_visible,
            'allow_coach_enrollment' => $event->allow_coach_enrollment,
            'allow_individual_enrollment' => $event->allow_individual_enrollment,
            'allow_referee_enrollment' => $event->allow_referee_enrollment,
            'competition' => $this->whenLoaded('competition', function () {
                $event = $this->resource;
                assert($event instanceof Event);

                return $event->competition ? [
                    'id' => $event->competition->id,
                    'full_name' => $event->competition->full_name,
                    'environment' => $event->competition->environment,
                    'types' => $event->competition->types_names,
                    'max_disciplines_per_athlete' => $event->competition->max_disciplines_per_athlete,
                    'max_relays_per_athlete' => $event->competition->max_relays_per_athlete,
                    'max_teams_per_athlete' => $event->competition->max_teams_per_athlete,
                    'sport' => $event->competition->sport ? [
                        'id' => $event->competition->sport->id,
                        'name' => $event->competition->sport->name,
                    ] : null,
                ] : null;
            }),
        ];
    }
}
