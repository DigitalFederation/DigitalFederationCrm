<?php

namespace App\Livewire;

use App\Models\Country;
use Livewire\Component;

class MapLocationComponent extends Component
{
    public $latitude = '47.59397';

    public $longitude = '14.12456';

    public $zoom = 5;

    public $existingLocations = [];
    public $publicLocations = [];

    public $listeners = ['countryChanged' => 'updateMapCenter'];

    public function setCoordinates($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->dispatch('coordinatesSet', $latitude, $longitude);
    }

    public function mount($divingLocation, $existingLocations = [], $publicLocations = [])
    {
        $this->existingLocations = $existingLocations;
        $this->publicLocations = $publicLocations;

        $this->latitude = $divingLocation?->lat;
        $this->longitude = $divingLocation?->lng;
        if ($this->latitude != null && $this->longitude != null) {
            $this->setCoordinates($this->latitude, $this->longitude);
            $this->zoom = ($divingLocation['id']) ? 11 : 6;
        } else {
            $this->setCoordinates(47.59397, 14.12456);
        }
        // $this->listeners = ['countryChanged' => 'updateMapCenter'];
    }

    /**
     * TODO: replace this for a lat/lng on the Country table
     *
     * @param [type] $countryName
     * @return void
     */
    public function updateMapCenter($countryId)
    {
        $country = Country::find($countryId);

        if ($country && $country->lat && $country->lng) {
            $latitude = $country->lat;
            $longitude = $country->lng;

            $this->dispatch('setMapCenter', $latitude, $longitude);
        }
    }

    public function render()
    {
        return view('livewire.map-location-component');
    }
}
