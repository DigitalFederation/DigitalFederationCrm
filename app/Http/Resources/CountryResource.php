<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ioc' => $this->ioc,
            'region_name' => $this->region_name,
            'sub_region_name' => $this->sub_region_name,
            'supported' => $this->supported,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'districts' => $this->whenLoaded('districts'),
        ];
    }
}
