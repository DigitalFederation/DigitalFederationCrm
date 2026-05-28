<section class=" bg-white shadow-md rounded-lg p-6 w-full">

    <div class="flex items-center justify-between">
        <h2 class="text-xl text-gray-800">Dive Location</h2>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
        </svg>
    </div>
    <div class="flex flex-col gap-2 mb-4">
        <div class="mt-4">
            <p class="text-gray-600">{{ $location->name }}</p>
        </div>
        @if($location->country)
        <div class="flex items-center gap-2">
            <span class="text-gray-600">{{ $location->country['name'] }}</span>
            @if($location->country['ioc'])
                <span class="text-gray-500 text-sm">({{ strtoupper($location->country['ioc']) }})</span>
            @endif
        </div>
        @endif
    </div>
    <div class="mt-4">
        <!-- Create a static map using LeafletJS -->
        <div id="map" class="w-full h-40 md:h-64"></div>
    </div>
</section>

@push('head-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
@endpush

@push('footer-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script type="text/javascript">
        window.onload = function () {
            let map = L.map('map').setView([{{ $location?->lat }}, {{ $location?->lng }}], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 12,
            }).addTo(map);
            L.marker([{{ $location?->lat }}, {{ $location?->lng }}]).addTo(map);
        }
    </script>
@endpush
