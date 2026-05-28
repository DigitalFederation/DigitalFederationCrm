<div>

    <div wire:ignore id="map" style="height: 400px;"></div>

    @push('head-css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.css" />
        <style>
            .existing-location-icon {
                background-color: rgba(0, 115, 255, 0.6); /* Blue with some transparency */
                border: 1px solid rgba(0, 115, 255, 0.8);
                width: 12px;
                height: 12px;
                display: block;
                left: -6px;
                top: -6px;
                position: relative;
                border-radius: 50%;
                box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            }
            .public-location-icon {
                background-color: rgba(100, 100, 100, 0.6); /* Gray with some transparency */
                border: 1px solid rgba(100, 100, 100, 0.8);
                width: 10px; /* Slightly smaller */
                height: 10px;
                display: block;
                left: -5px;
                top: -5px;
                position: relative;
                border-radius: 50%;
                box-shadow: 1px 1px 2px rgba(0,0,0,0.4);
            }
        </style>
    @endpush

    @push('footer-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                const map = L.map('map').setView([{{$latitude}}, {{$longitude}}], {{$zoom}});
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                let marker = L.marker([{{$latitude}}, {{$longitude}}], { draggable: true }).addTo(map);

                // Define custom icon for existing locations
                const existingIcon = L.divIcon({
                    className: 'existing-location-icon',
                    iconSize: [12, 12],
                });

                // Define custom icon for public locations
                const publicIcon = L.divIcon({
                    className: 'public-location-icon',
                    iconSize: [10, 10],
                });

                // Initialize marker cluster group for existing locations
                const existingMarkers = L.markerClusterGroup();

                // Display existing locations
                const existingLocations = @json($existingLocations);
                existingLocations.forEach(location => {
                    if (location.lat && location.lng) {
                        const existingMarker = L.marker([location.lat, location.lng], {
                           icon: existingIcon // Use the custom icon
                        })
                        .bindTooltip(location.name); // Show name on hover
                        existingMarkers.addLayer(existingMarker); // Add to cluster group
                    }
                });

                // Display public locations (add to the same cluster group)
                const publicLocations = @json($publicLocations);
                publicLocations.forEach(location => {
                    if (location.lat && location.lng) {
                        const publicMarker = L.marker([location.lat, location.lng], {
                           icon: publicIcon // Use the public icon
                        })
                        .bindTooltip(location.name + ' (Public)'); // Add (Public) to tooltip
                        existingMarkers.addLayer(publicMarker); // Add to the SAME cluster group
                    }
                });

                map.addLayer(existingMarkers); // Add the cluster group to the map

                map.on('click', (e) => {
                    const latitude = e.latlng.lat;
                    const longitude = e.latlng.lng;

                    document.getElementById('latitudeInput').value = latitude;
                    document.getElementById('longitudeInput').value = longitude;

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker(e.latlng).addTo(map);
                });

                document.addEventListener('DOMContentLoaded', function () {
                    window.livewire.on('setMapCenter', (latitude, longitude) => {
                        setMapCenter(map, latitude, longitude);
                    });
                });

            });

            function setMapCenter(map, latitude, longitude){
                const lat = parseFloat(latitude);
                const lng = parseFloat(longitude);

                setTimeout(() => {
                    map.setView([lat, lng], 5);
                }, 100);
            }
        </script>
    @endpush


</div>
