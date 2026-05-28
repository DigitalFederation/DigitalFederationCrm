<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'event_type' => $this->event_type,
            'event_category' => $this->event_category,
            'location' => $this->location,
            'venue' => $this->venue,
            'venue_address' => $this->venue_address,
            'venue_city' => $this->venue_city,
            'venue_country' => $this->whenLoaded('venueCountry', function () {
                return [
                    'id' => $this->venueCountry->id,
                    'name' => $this->venueCountry->name,
                    'code' => $this->venueCountry->code,
                ];
            }),
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'start_registration' => $this->start_registration?->format('Y-m-d'),
            'end_registration' => $this->end_registration?->format('Y-m-d'),
            'other_deadlines' => $this->other_deadlines,
            'featured_image' => $this->featured_image,
            'description' => $this->description,
            'external_url' => $this->external_url,
            'status' => $this->getStateLabel(),
            'is_visible' => $this->is_visible,
            'allow_coach_enrollment' => $this->allow_coach_enrollment,
            'allow_individual_enrollment' => $this->allow_individual_enrollment,
            'allow_referee_enrollment' => $this->allow_referee_enrollment,
            'competition' => $this->whenLoaded('competition', function () {
                return [
                    'id' => $this->competition->id,
                    'full_name' => $this->competition->full_name,
                    'environment' => $this->competition->environment,
                    'types' => $this->competition->types_names,
                    'max_disciplines_per_athlete' => $this->competition->max_disciplines_per_athlete,
                    'max_relays_per_athlete' => $this->competition->max_relays_per_athlete,
                    'max_teams_per_athlete' => $this->competition->max_teams_per_athlete,
                    'sport' => $this->competition->sport ? [
                        'id' => $this->competition->sport->id,
                        'name' => $this->competition->sport->name,
                    ] : null,
                ];
            }),
        ];
    }
}
