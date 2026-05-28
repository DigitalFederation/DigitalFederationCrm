<?php

namespace App\Livewire;

use App\Models\Country;
use Domain\DivingLogs\Models\DivingLocation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectLocationComponent extends Component
{
    public $isListView = false;

    public $shouldInitializeMapOnLoad = true;

    public $divingLocations = [];

    public $location_id;

    public $countries;

    public $selectedCountry;

    public $minLat;

    public $minLng;

    public $maxLat;

    public $maxLng;

    public $location;

    public $locations;

    public $defaultLat = 0;
    public $defaultLng = 0;
    public $defaultZoom = 2;

    public function mount($location)
    {
        $this->countries = Country::all();
        $this->location = (object) $location; // Ensure it's always an object
        $this->selectedCountry = $this->location->country_id ?? null;

        // Set default values if location is not provided
        if (! $this->location || ! isset($this->location->lat) || ! isset($this->location->lng)) {
            $this->defaultLat = 0;
            $this->defaultLng = 0;
            $this->defaultZoom = 2;
        } else {
            $this->defaultLat = $this->location->lat;
            $this->defaultLng = $this->location->lng;
            $this->defaultZoom = $this->location->zoom ?? 6;
        }
    }

    public function toggleView()
    {
        $this->isListView = ! $this->isListView;
        if ($this->isListView) {
            if ($this->selectedCountry) {
                $this->divingLocations = DivingLocation::where('country_id', $this->selectedCountry)->get()->toArray();
            }
        } else {
            $this->dispatch('initializeMap');
            $this->divingLocations = collect();
        }
        $this->dispatch('updateDivingLocations', $this->divingLocations);
    }

    public function updateDivingLocations($locations)
    {
        $this->divingLocations = $locations;
    }

    public function loadDivingLocations()
    {
        $user = Auth::user();
        $this->locations = DivingLocation::whereBetween('lat', [$this->minLat, $this->maxLat])
            ->whereBetween('lng', [$this->minLng, $this->maxLng])
            ->where(function ($q) use ($user) {
                if ($user->individual != null) {
                    $q->where('owner_id', $user->individual->id)
                        ->orWhere('owner_type', 'Domain\Federations\Models\Federation')
                        ->orWhere('owner_type', 'Domain\Entities\Models\Entity');
                }
            })
            ->get();

        $locationsArray = $this->locations->toArray();
        $this->dispatch('renderDivingLocations', ['locations' => $locationsArray]);
    }

    public function countrySelected()
    {
        if ($this->selectedCountry) {
            $this->divingLocations = DivingLocation::where('country_id', $this->selectedCountry)->get()->toArray();
        } else {
            $this->divingLocations = [];
        }

        $country = $this->countries->firstWhere('id', $this->selectedCountry);
        if ($country && $country->lat && $country->lng) {
            $this->dispatch('setMapCenter', [$country->lat, $country->lng]);
        }
    }

    #[On('selectDivingLocation')]
    public function selectDivingLocation($id)
    {

        if (is_numeric($id)) {
            $this->location_id = $id;
            $this->dispatch('setLocation', $id);

            // Fetch the selected location and update the $location property
            $selectedLocation = DivingLocation::find($id);
            if ($selectedLocation) {
                $this->location = $selectedLocation;
                $this->selectedCountry = $selectedLocation->country_id;
            }
        }
    }

    #[On('updateMapBounds')]
    public function updateMapBounds($minLat, $minLng, $maxLat, $maxLng)
    {

        $this->minLat = $minLat;
        $this->minLng = $minLng;
        $this->maxLat = $maxLat;
        $this->maxLng = $maxLng;

        $this->loadDivingLocations();
    }

    public function render()
    {
        // Ensure divingLocations is always an array
        $this->divingLocations = is_array($this->divingLocations) ? $this->divingLocations : [];

        // Ensure location is always an object
        if (! is_object($this->location)) {
            $this->location = (object) $this->location;
        }

        return view('livewire.select-location-component', [
            'divingLocations' => $this->divingLocations,
            'location_id' => $this->location_id,
            'location' => $this->location,
        ]);
    }
}
