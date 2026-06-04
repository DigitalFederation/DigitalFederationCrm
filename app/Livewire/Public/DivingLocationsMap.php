<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Country;
use App\Models\User;
use Domain\DivingLogs\Models\DivingLocation;
use Domain\Entities\Models\Entity;
use Domain\Federations\Models\Federation;
use Domain\Geographic\Models\District;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DivingLocationsMap extends Component
{
    public $selectedCountry = '';
    public $selectedDistrict = '';
    public $searchTerm = '';

    // New Filter Properties
    public array $selectedWaterTypes = [];
    public array $selectedDiveTypes = [];
    public array $selectedLevels = [];
    public string $depthSearch = '';

    // Define available filter options (can be dynamic later)
    public array $waterTypeOptions = ['Salt Water', 'Fresh Water', 'Brackish Water'];
    public array $diveTypeOptions = ['Open Water', 'Inland Waters', 'Wall or Canyon', 'Grotto', 'Cave', 'Wreck', 'Pool'];
    public array $levelOptions = ['Beginner', 'Intermediate', 'Advanced', 'Technical'];

    // Store the selected item details locally for the modal
    public ?array $selectedItemDetailsForModal = null;

    // Store map bounds from frontend
    public ?array $mapBounds = null;

    // Store nearby entities (was dive centers)
    public array $nearbyEntities = [];

    /**
     * Maximum number of locations to return to prevent abuse
     */
    protected const MAX_LOCATIONS = 2000;

    /**
     * Cache TTL values for different types of data
     */
    protected const CACHE_TTL = [
        'countries' => 86400, // 24 hours
        'locations' => 1800,  // 30 minutes
        'counts' => 1800,     // 30 minutes
        'item_details' => 3600, // 1 hour
    ];

    protected array $queryString = [
        'selectedCountry' => ['except' => ''],
        'selectedDistrict' => ['except' => ''],
        'searchTerm' => ['except' => ''],
        'water' => ['as' => 'selectedWaterTypes', 'except' => []],
        'diveType' => ['as' => 'selectedDiveTypes', 'except' => []],
        'level' => ['as' => 'selectedLevels', 'except' => []],
        'depth' => ['as' => 'depthSearch', 'except' => ''],
    ];

    public function mount(?int $locationId = null): void
    {
        if ($locationId) {
            // Load the specific location details, which will also trigger map centering and highlighting
            $this->showDetails($locationId);
            // Also dispatch the initial full list for the sidebar
            $this->dispatch('updateLocations', locations: $this->mapLocations);
        } else {
            // Default: load initial locations for the map if no specific ID is given
            $this->dispatch('updateLocations', locations: $this->mapLocations);
        }
    }

    public function updated(string $property): void
    {
        foreach (['selectedCountry', 'selectedDistrict', 'searchTerm', 'depthSearch'] as $scalar) {
            if (is_array($this->$scalar)) {
                $this->$scalar = (string) (reset($this->$scalar) ?: '');
            }
        }

        if (in_array($property, ['selectedCountry', 'selectedDistrict', 'searchTerm', 'selectedWaterTypes', 'selectedDiveTypes', 'selectedLevels', 'depthSearch'])) {
            // If filters change, clear map bounds so sidebar shows filtered results, not bounds results
            $this->mapBounds = null;
            $this->dispatch('updateLocations', locations: $this->mapLocations);
            $this->dispatch('resetMap'); // Reset map to default view when filters change
        }

        // When country changes, reset district filter
        if ($property === 'selectedCountry') {
            $this->selectedDistrict = '';
        }
    }

    /**
     * Public method to initialize map data for JavaScript
     */
    public function initializeMap(): void
    {
        $this->dispatch('updateLocations', locations: $this->mapLocations);
    }

    public function resetMap(): void
    {
        // Reset map to default view
        $this->dispatch('centerMap', [
            'lat' => 20,
            'lng' => 0,
            'zoom' => 2,
        ]);
    }

    public function updateCountry(string $countryId): void
    {
        $this->selectedCountry = $countryId;

        if ($countryId) {
            $country = Country::query()->find($countryId);
            if ($country && $country->lat && $country->lng) {
                $this->dispatch('centerMap', [
                    'lat' => (float) $country->lat,
                    'lng' => (float) $country->lng,
                    'zoom' => 6,
                ]);
            }
        } else {
            $this->dispatch('centerMap', [
                'lat' => 20,
                'lng' => 0,
                'zoom' => 2,
            ]);
        }

        // No need to dispatch updateLocations here, 'updated' hook handles it
    }

    #[Computed]
    public function countries(): Collection
    {
        $cacheKey = 'countries_with_diving_locations';

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL['countries']), function () {
            // Only list countries that actually have public diving locations
            return Country::query()
                ->whereHas('divingLocations', function ($query) {
                    $query->whereIn('owner_type', [Federation::class, Entity::class, User::class])
                        ->whereNotNull(['lat', 'lng']);
                })
                ->orderBy('name')
                ->get(['id', 'name', 'lat', 'lng']);
        });
    }

    #[Computed]
    public function districts(): Collection
    {
        // If a country is selected, show districts for that country
        // Otherwise, show districts for all countries that have diving locations
        $countryId = $this->selectedCountry;

        if (! $countryId) {
            // Get the first country with diving locations (default to Portugal)
            $defaultCountry = $this->countries->first();
            if (! $defaultCountry) {
                return collect();
            }
            $countryId = $defaultCountry->id;
        }

        $cacheKey = sprintf('districts_for_country_%s_v2', $countryId);

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL['countries']), function () use ($countryId) {
            return District::query()
                ->where('country_id', $countryId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']);
        });
    }

    /**
     * Coerce the multi-select filter properties into flat lists of scalar values.
     *
     * Livewire can hydrate these `array` properties with nested arrays from
     * malformed query strings; passing those straight to whereIn() throws
     * "Nested arrays may not be passed to whereIn method." Normalising here keeps
     * every query path safe regardless of how the value was hydrated.
     */
    private function normaliseSelectedFilters(): void
    {
        $this->selectedWaterTypes = $this->normaliseFilterValues($this->selectedWaterTypes);
        $this->selectedLevels = $this->normaliseFilterValues($this->selectedLevels);
        $this->selectedDiveTypes = $this->normaliseFilterValues($this->selectedDiveTypes);
    }

    /**
     * Flatten a Livewire array filter into a de-duplicated list of non-empty scalars.
     */
    private function normaliseFilterValues(mixed $values): array
    {
        return array_values(array_unique(array_filter(
            Arr::flatten(Arr::wrap($values)),
            static fn ($value) => is_scalar($value) && $value !== '',
        )));
    }

    #[Computed]
    public function mapLocations(): array
    {
        $this->normaliseSelectedFilters();

        $cacheKey = 'diving_locations_v6_' . md5(json_encode([
            $this->selectedCountry,
            $this->selectedDistrict,
            $this->searchTerm,
            $this->selectedWaterTypes,
            $this->selectedDiveTypes,
            $this->selectedLevels,
            $this->depthSearch,
        ]));

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL['locations']), function () {
            $query = DivingLocation::query()
                ->whereIn('owner_type', [Federation::class, Entity::class, User::class])
                ->whereNotNull(['lat', 'lng'])
                ->with(['country:id,name,iso', 'district:id,name']);

            if ($this->selectedCountry) {
                $query->where('country_id', $this->selectedCountry);
            }

            if ($this->selectedDistrict) {
                $query->where('district_id', $this->selectedDistrict);
            }

            if ($this->searchTerm) {
                $term = "%{$this->searchTerm}%";
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('region', 'like', $term)
                        ->orWhere('native_name', 'like', $term)
                        ->orWhereHas('country', fn ($cq) => $cq->where('name', 'like', $term));
                });
            }

            // Apply Water Type Filter
            if (! empty($this->selectedWaterTypes)) {
                $query->whereIn('water_type', $this->selectedWaterTypes);
            }

            // Apply Level Filter (JSON array field)
            if (! empty($this->selectedLevels)) {
                $query->where(function ($q) {
                    foreach ($this->selectedLevels as $level) {
                        $q->orWhereJsonContains('level', $level);
                    }
                });
            }

            // Apply Dive Type Filter (JSON array field)
            if (! empty($this->selectedDiveTypes)) {
                $query->where(function ($q) {
                    foreach ($this->selectedDiveTypes as $diveType) {
                        $q->orWhereJsonContains('dive_type', $diveType);
                    }
                });
            }

            // Apply Depth Text Filter
            if (! empty($this->depthSearch)) {
                $query->where('depth', 'like', '%' . $this->depthSearch . '%');
            }

            // Limit the number of results to prevent abuse
            return $query->limit(self::MAX_LOCATIONS)
                ->get()
                ->map(fn (DivingLocation $location) => [
                    'id' => $location->id,
                    'name' => $location->name,
                    'native_name' => $location->native_name,
                    'region' => $location->region,
                    'country' => $location->country?->name,
                    'country_code' => $location->country?->iso,
                    'district' => $location->district?->name,
                    'lat' => (float) $location->lat,
                    'lng' => (float) $location->lng,
                    'notes' => $location->notes ?? null,
                ])
                ->toArray();
        });
    }

    /**
     * Checks if any user-configurable filters are currently active.
     */
    protected function hasActiveFilters(): bool
    {
        return ! empty($this->selectedCountry)
            || ! empty($this->selectedDistrict)
            || ! empty($this->searchTerm)
            || ! empty($this->selectedWaterTypes)
            || ! empty($this->selectedDiveTypes)
            || ! empty($this->selectedLevels)
            || ! empty($this->depthSearch);
    }

    #[Computed]
    public function sidebarList(): array
    {
        // 1. If filters are active, show the filtered list (same as map)
        if ($this->hasActiveFilters()) {
            return $this->mapLocations(); // Reuse the computed property for map locations
        }

        // 2. If no filters are active, but we have valid map bounds, query within bounds
        if ($this->mapBounds && isset($this->mapBounds['sw']['lat'], $this->mapBounds['ne']['lat'], $this->mapBounds['sw']['lng'], $this->mapBounds['ne']['lng'])) {
            $bounds = $this->mapBounds;
            $cacheKey = sprintf(
                'diving_locations_sidebar_bounds_%s_%s_%s_%s_v2', // Version up
                md5((string) $bounds['sw']['lat']),
                md5((string) $bounds['sw']['lng']),
                md5((string) $bounds['ne']['lat']),
                md5((string) $bounds['ne']['lng'])
            );

            return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL['locations'] / 4), function () use ($bounds) { // Shorter cache for bounds
                // Basic bounding box query (ignores antimeridian wrapping)
                $query = DivingLocation::query()
                    ->whereIn('owner_type', [Federation::class, Entity::class, User::class])
                    ->whereNotNull(['lat', 'lng'])
                    ->whereBetween('lat', [$bounds['sw']['lat'], $bounds['ne']['lat']])
                    ->whereBetween('lng', [$bounds['sw']['lng'], $bounds['ne']['lng']]) // Note: This won't work perfectly across the antimeridian
                    ->with(['country:id,name,iso']) // Removed owner relationship
                    ->orderByRaw('ST_Distance_Sphere(point(lng, lat), point(?, ?))', [
                        ($bounds['sw']['lng'] + $bounds['ne']['lng']) / 2, // Center longitude
                        ($bounds['sw']['lat'] + $bounds['ne']['lat']) / 2,  // Center latitude
                    ]) // Order by distance from viewport center (requires DB spatial functions)
                    ->limit(50); // Limit results for sidebar performance

                return $query->get()->map(fn (DivingLocation $location) => [
                    'id' => $location->id,
                    'name' => $location->name,
                    'native_name' => $location->native_name,
                    'region' => $location->region,
                    'country' => $location->country?->name,
                    'country_code' => $location->country?->iso,
                    'lat' => (float) $location->lat,
                    'lng' => (float) $location->lng,
                    'notes' => $location->notes ?? null, // Add notes field
                    // Removed owner_name and owner_type
                ])->toArray();
            });
        }

        // 3. Fallback: No filters, no valid bounds -> Show latest 10
        $cacheKey = 'diving_locations_latest_10_sidebar_v2'; // Version up

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL['locations'] / 2), function () { // Shorter cache for latest
            return DivingLocation::query()
                ->whereIn('owner_type', [Federation::class, Entity::class, User::class])
                ->whereNotNull(['lat', 'lng'])
                ->with(['country:id,name,iso']) // Removed owner relationship
                ->latest() // Order by creation date descending
                ->limit(10)
                ->get()
                ->map(fn (DivingLocation $location) => [
                    'id' => $location->id,
                    'name' => $location->name,
                    'native_name' => $location->native_name,
                    'region' => $location->region,
                    'country' => $location->country?->name,
                    'country_code' => $location->country?->iso,
                    'lat' => (float) $location->lat,
                    'lng' => (float) $location->lng,
                    'notes' => $location->notes ?? null, // Add notes field
                    // Removed owner_name and owner_type
                ])
                ->toArray();
        });
    }

    #[Computed]
    public function totalLocations(): int
    {
        $this->normaliseSelectedFilters();

        $cacheKey = 'diving_locations_count_v5_' . md5(json_encode([
            $this->selectedCountry,
            $this->selectedDistrict,
            $this->searchTerm,
            $this->selectedWaterTypes,
            $this->selectedDiveTypes,
            $this->selectedLevels,
            $this->depthSearch,
        ]));

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL['counts']), function () {
            $query = DivingLocation::query()
                ->whereIn('owner_type', [Federation::class, Entity::class, User::class])
                ->whereNotNull(['lat', 'lng']);

            if ($this->selectedCountry) {
                $query->where('country_id', $this->selectedCountry);
            }

            if ($this->selectedDistrict) {
                $query->where('district_id', $this->selectedDistrict);
            }

            if ($this->searchTerm) {
                $term = "%{$this->searchTerm}%";
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('region', 'like', $term)
                        ->orWhere('native_name', 'like', $term)
                        ->orWhereHas('country', fn ($cq) => $cq->where('name', 'like', $term));
                });
            }

            // Apply Water Type Filter
            if (! empty($this->selectedWaterTypes)) {
                $query->whereIn('water_type', $this->selectedWaterTypes);
            }

            // Apply Level Filter (JSON array field)
            if (! empty($this->selectedLevels)) {
                $query->where(function ($q) {
                    foreach ($this->selectedLevels as $level) {
                        $q->orWhereJsonContains('level', $level);
                    }
                });
            }

            // Apply Dive Type Filter (JSON array field)
            if (! empty($this->selectedDiveTypes)) {
                $query->where(function ($q) {
                    foreach ($this->selectedDiveTypes as $diveType) {
                        $q->orWhereJsonContains('dive_type', $diveType);
                    }
                });
            }

            // Apply Depth Text Filter
            if (! empty($this->depthSearch)) {
                $query->where('depth', 'like', '%' . $this->depthSearch . '%');
            }

            return min($query->count(), self::MAX_LOCATIONS);
        });
    }

    public function showDetails(int $id): void
    {
        $cacheKey = sprintf('diving_location_details_%d_v3', $id);

        $locationData = Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL['item_details']), function () use ($id) {
            // If the location is in our current mapLocations, use that data
            // This avoids unnecessary database queries
            // We still need to fetch details like depth, water_type, level, image if not already present
            $cachedLocation = collect($this->mapLocations)->firstWhere('id', $id);

            // Fetch full details from database regardless of cache for now,
            // ensuring all fields including image are present.
            // Optimization: Could check if cachedLocation has all needed fields first.
            $location = DivingLocation::query()
                ->with(['country:id,name,iso', 'media']) // No longer eager load owner
                ->find($id, [
                    'id',
                    'name',
                    'native_name',
                    'region',
                    'country_id',
                    'lat',
                    'lng',
                    'depth',
                    'water_type',
                    'dive_type',
                    'level',
                    'notes',
                ]);

            if (! $location) {
                return null;
            }

            // Get the image URL
            $imageUrl = $location->getFirstMediaUrl('diving-location-images', 'preview'); // Use 'preview' conversion

            // Convert level array to translated comma-separated string
            $levelDisplay = null;
            if (is_array($location->level) && ! empty($location->level)) {
                $levelDisplay = implode(', ', array_map(fn ($l) => __('diving_location.' . str_replace(' ', '_', strtolower($l))), $location->level));
            } elseif (is_string($location->level)) {
                $levelDisplay = __('diving_location.' . str_replace(' ', '_', strtolower($location->level)));
            }

            // Convert dive_type array to translated comma-separated string
            $diveTypeDisplay = null;
            if (is_array($location->dive_type) && ! empty($location->dive_type)) {
                $diveTypeDisplay = implode(', ', array_map(fn ($t) => __('diving_location.' . str_replace(' ', '_', strtolower($t))), $location->dive_type));
            } elseif (is_string($location->dive_type)) {
                $diveTypeDisplay = __('diving_location.' . str_replace(' ', '_', strtolower($location->dive_type)));
            }

            return [
                'id' => $location->id,
                'name' => $location->name,
                'native_name' => $location->native_name,
                'region' => $location->region,
                'country' => $location->country?->name,
                'country_code' => $location->country?->iso,
                'lat' => (float) $location->lat,
                'lng' => (float) $location->lng,
                'depth' => $location->depth,
                'water_type' => $location->water_type,
                'dive_type' => $diveTypeDisplay,
                'level' => $levelDisplay,
                'notes' => $location->notes ?? null,
                'image_url' => $imageUrl ?: null,
            ];
        });

        if ($locationData) {
            $this->selectedItemDetailsForModal = $locationData; // Store details for modal rendering

            // Fetch nearby entities (was dive centers)
            $this->fetchNearbyEntities($locationData['lat'], $locationData['lng']);

            $this->dispatch('highlightMarker', [
                'id' => $id,
                'type' => 'diving_location',
            ]);

            $this->dispatch('centerMap', [
                'lat' => $locationData['lat'],
                'lng' => $locationData['lng'],
                'zoom' => 12,
            ]);

            // Dispatch browser event to open modal
            $this->dispatch('open-diving-location-modal');
        } else {
            $this->dispatch('showNotification', ['message' => __('Location details not found.'), 'type' => 'error']);
        }
    }

    public function closeModal(): void
    {
        $this->selectedItemDetailsForModal = null; // Clear details
        $this->nearbyEntities = []; // Clear nearby entities (was centers)
        $this->dispatch('removeHighlight');
        // Dispatch browser event to close modal
        $this->dispatch('close-diving-location-modal');
    }

    /**
     * Clear all applied filters.
     */
    public function clearFilters(): void
    {
        $this->reset(['selectedCountry', 'selectedDistrict', 'searchTerm', 'selectedWaterTypes', 'selectedDiveTypes', 'selectedLevels', 'depthSearch']);
        $this->mapBounds = null; // Clear bounds when filters are cleared
        $this->resetMap(); // Also reset the map view
        // The 'updated' hook will trigger recalculations and dispatches
    }

    /**
     * Update the map bounds from the frontend.
     */
    public function updateBounds(array $boundsData): void
    {
        // Basic validation
        if (
            isset($boundsData['ne']['lat'], $boundsData['ne']['lng'], $boundsData['sw']['lat'], $boundsData['sw']['lng']) &&
            is_numeric($boundsData['ne']['lat']) && is_numeric($boundsData['ne']['lng']) &&
            is_numeric($boundsData['sw']['lat']) && is_numeric($boundsData['sw']['lng'])
        ) {
            $this->mapBounds = [
                'ne' => ['lat' => (float) $boundsData['ne']['lat'], 'lng' => (float) $boundsData['ne']['lng']],
                'sw' => ['lat' => (float) $boundsData['sw']['lat'], 'lng' => (float) $boundsData['sw']['lng']],
            ];
            // Note: We don't dispatch updates here; the change to $mapBounds will trigger
            // the re-computation of the sidebarList computed property automatically.
        } else {
            // Log error or handle invalid data
            logger()->warning('Received invalid map bounds data', ['data' => $boundsData]);
        }
    }

    // --- Share Methods ---

    /**
     * Prepare data and dispatch event to copy location link to clipboard.
     */
    public function copyLink(int $id): void
    {
        $location = $this->getLocationDataForSharing($id);
        if ($location) {
            // You might want a dedicated route for individual location views
            $url = url()->route('public.diving-locations', ['location' => $id]); // Use correct route name
            $text = sprintf('%s: %s', $location['name'], $url);
            $this->dispatch('copy-to-clipboard', text: $text);
            $this->dispatch('showNotification', ['message' => __('Link copied to clipboard!'), 'type' => 'success']);
        } else {
            $this->dispatch('showNotification', ['message' => __('Could not generate link for this location.'), 'type' => 'error']);
        }
    }

    /**
     * Prepare data and dispatch event to open WhatsApp share link.
     */
    public function shareViaWhatsApp(int $id): void
    {
        $location = $this->getLocationDataForSharing($id);
        if ($location) {
            $url = url()->route('public.diving-locations', ['location' => $id]); // Use correct route name
            $text = sprintf('Check out this diving location: %s - %s', $location['name'], $url);
            $whatsappUrl = 'https://wa.me/?text=' . urlencode($text);
            // Dispatch event for JS to handle opening the URL
            $this->dispatch('open-url', url: $whatsappUrl, target: '_blank');
        } else {
            $this->dispatch('showNotification', ['message' => __('Could not generate WhatsApp link.'), 'type' => 'error']);
        }
    }

    /**
     * Prepare data and dispatch event for Web Share API or fallback.
     */
    public function shareSocial(int $id): void
    {
        $location = $this->getLocationDataForSharing($id);
        if ($location) {
            $url = url()->route('public.diving-locations', ['location' => $id]); // Use correct route name
            $title = $location['name'];
            $text = sprintf('Check out this diving location: %s', $location['name']);
            // Dispatch event for JS to handle Web Share API
            $this->dispatch('web-share', ['title' => $title, 'text' => $text, 'url' => $url]);
        } else {
            $this->dispatch('showNotification', ['message' => __('Could not prepare sharing details.'), 'type' => 'error']);
        }
    }

    /**
     * Helper method to get minimal location data needed for sharing actions.
     * Avoids fetching full details if not already loaded in the modal.
     */
    private function getLocationDataForSharing(int $id): ?array
    {
        // Try fetching from existing modal data first for efficiency
        if ($this->selectedItemDetailsForModal && $this->selectedItemDetailsForModal['id'] === $id) {
            // Ensure 'name' key exists, adjust if your modal data structure is different
            return ['id' => $this->selectedItemDetailsForModal['id'], 'name' => $this->selectedItemDetailsForModal['name'] ?? 'Unknown Location'];
        }

        // If not in modal, fetch minimal data from DB (or cache if implemented)
        // Consider adding caching here if needed
        $location = DivingLocation::query()->find($id, ['id', 'name']);

        return $location ? $location->toArray() : null;
    }

    /**
     * Fetch nearby entities based on coordinates.
     */
    private function fetchNearbyEntities(float $latitude, float $longitude, int $limit = 5): void
    {
        // Get the diving location ID based on coordinates
        $divingLocation = DivingLocation::where('lat', $latitude)
            ->where('lng', $longitude)
            ->first();

        if (! $divingLocation) {
            $this->nearbyEntities = [];

            return;
        }

        // Get entities that have featured this specific diving location
        $nearbyEntities = $divingLocation->featuringEntities()
            ->with('country:id,name') // Eager load country name
            ->select(
                'entity.id',
                'entity.name',
                'entity.lat',
                'entity.lng',
                'entity.address',
                'entity.location',
                'entity.postal_code',
                'entity.country_id',
                'entity.phone',
                'entity.website',
                'entity.email'
            )
            ->limit($limit)
            ->get();

        // Format the results for the view
        $this->nearbyEntities = $nearbyEntities->map(function (Entity $entity) {
            return [
                'id' => $entity->id,
                'name' => $entity->name,
                'address' => $entity->address,
                'location' => $entity->location,
                'postal_code' => $entity->postal_code,
                'country' => $entity->country?->name,
                'phone' => $entity->phone,
                'website' => $entity->website,
                'email' => $entity->email,
                'is_featured' => true, // Always true since we're filtering for featured locations
            ];
        })->values()->toArray();
    }

    public function render(): View
    {
        return view('livewire.public.diving-locations-map.index')->layout('layouts.public', [
            'title' => __('public.diving_locations.title'),
            'currentPage' => 'diving-locations',
        ]);
    }
}
