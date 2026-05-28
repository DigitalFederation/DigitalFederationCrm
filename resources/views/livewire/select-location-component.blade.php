<div>

    <div class="w-full mb-2 mt-2">
        <label class="block text-sm font-medium mb-1" for="country">{{__('Country') }} <span
                class="text-rose-500">*</span></label>
        <select wire:ignore wire:model.live="selectedCountry" wire:change="countrySelected" id="country"
                class="form-select w-full" required>
            <option value="" selected disabled> {{ __('-- Select an option --') }} </option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}">{{ $country->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="w-full flex justify-between items-center mb-2">
        <button type="button" wire:click="toggleView" class="btn-sm btn-info">
            {{ $isListView ? 'Switch to Map View' : 'Switch to List View' }}
        </button>
    </div>

    <!-- Show a notice that a location is selected -->
    <div class="w-full">
        @if($location_id)
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <strong class="font-bold"> {{ __('Location selected') }} </strong>
                <span
                    class="block sm:inline"> {{ __('You have selected a location. You can change it by selecting another location from the list or map.') }} </span>
            </div>
        @endif
    </div>

    @if ($isListView)
        <!-- List View -->
        <div class="w-full">
            @if(!empty($divingLocations))
                <ul class="border border-gray-200 h-80 overflow-scroll rounded-md">
                    @foreach($divingLocations as $location)
                        <li class="shadow-sm px-4 py-2 flex my-2 justify-between">
                            <div>
                                <p class="font-bold text-sm">{{ $location["name"] }} </p>
                                <p class="text-xs">{{ $location["region"] }} </p>
                            </div>
                            <button
                                type="button"
                                class="btn-sm btn-info"
                                wire:click="selectDivingLocation({{ $location['id'] }})">
                                Select
                            </button>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-sm text-gray-600 font-semibold italic"> The selected country doesn't have any diving
                    locations.
                </div>

            @endif
        </div>

    @else
        <!-- Map View -->
        <div
            wire:ignore
            id="divingLocationsMap"
            data-should-initialize-on-load="{{ $shouldInitializeMapOnLoad ? 'true' : 'false' }}"
            style="width: 100%; height: 400px;"
            data-default-lat="{{ $defaultLat }}"
            data-default-lng="{{ $defaultLng }}"
            data-default-zoom="{{ $defaultZoom }}">
        </div>

    @endif

    <input type="hidden" id="locationInput" name="divingLogArray.diving_location_id" wire:model.live="location_id" />


    @push('head-css')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    @endpush

    @push('footer-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
        <script>

            document.addEventListener("livewire:initialized", function() {

                let map;
                let initialized = false;
                const mapElement = document.getElementById("divingLocationsMap");


                const initializeMap = () => {
                    setTimeout(() => {
                        if (!mapElement || initialized) return;

                        const defaultLat = parseFloat(mapElement.dataset.defaultLat) || 0;
                        const defaultLng = parseFloat(mapElement.dataset.defaultLng) || 0;
                        const defaultZoom = parseInt(mapElement.dataset.defaultZoom) || 2;
                        // Validate coordinates
                        const validLat = isFinite(defaultLat) && Math.abs(defaultLat) <= 90;
                        const validLng = isFinite(defaultLng) && Math.abs(defaultLng) <= 180;
                        const validZoom = isFinite(defaultZoom) && defaultZoom >= 0 && defaultZoom <= 18;

                        const initialLat = validLat ? defaultLat : 0;
                        const initialLng = validLng ? defaultLng : 0;
                        const initialZoom = validZoom ? defaultZoom : 2;

                        map = L.map("divingLocationsMap").setView([initialLat, initialLng], initialZoom);

                        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                            attribution: "&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors"
                        }).addTo(map);


                        //renderDivingLocations(map, locations);
                        window.Livewire.on("renderDivingLocations", (data) => {
                            renderDivingLocationsAction(map, data[0].locations);
                        });

                        navigator.geolocation.getCurrentPosition(function(position) {
                            map.setView([position.coords.latitude, position.coords.longitude], 6);

                        });

                        map.whenReady(function() {
                            updateBoundsAndLocations(map);
                            map.on("moveend", () => {
                                updateBoundsAndLocations(map);
                            });
                        });

                        window.Livewire.on("setMapCenter", (location) => {
                            const lat = parseFloat(location[0]);
                            const lng = parseFloat(location[1]);
                            console.log(lat);

                            setTimeout(() => {
                                map.setView([lat, lng], 6);
                            }, 100);
                        });

                    }, 200);
                };


                window.Livewire.on("initializeMap", () => {
                    console.log("window.Livewire.on-initializeMap");
                    initializeMap();
                });

                document.addEventListener("click", (event) => {
                    if (event.target.matches(".selectDivingLocationBtn")) {
                        const locationId = event.target.getAttribute("data-location-id");
                        @this.
                        dispatchSelf("selectDivingLocationAction", [locationId]);
                    }
                });

                initializeMap();
                document.addEventListener("livewire:update", initializeMap);

            });

            //cluster map plugin
            const markerClusterGroup = L.markerClusterGroup({ maxClusterRadius: 10 });

            function renderDivingLocationsAction(map, locations) {
                if (Array.isArray(locations)) {
                    // Clear the existing markers in the cluster group
                    markerClusterGroup.clearLayers();

                    locations.forEach(location => {
                        const marker = L.marker([location.lat, location.lng]);
                        const selectButton = `<button type="button" class="mt-2 btn bg-sky-600 text-white w-full py-1" onclick="window.Livewire.dispatch('selectDivingLocation', [${location.id}])">Select</button>`;

                        marker.bindPopup(`<strong>${location.name}</strong><br>${selectButton}`).on("click", () => marker.openPopup());

                        // Add the marker to the cluster group
                        markerClusterGroup.addLayer(marker);
                    });

                    // Add the cluster group to the map
                    map.addLayer(markerClusterGroup);
                } else {
                    console.error("Invalid locations data:", locations);
                }
            }

            function updateBoundsAndLocations(map) {
                const bounds = map.getBounds();
                window.Livewire.dispatch("updateMapBounds", [bounds.getSouthWest().lat, bounds.getSouthWest().lng, bounds.getNorthEast().lat, bounds.getNorthEast().lng]);
            }

        </script>
    @endpush


</div>
