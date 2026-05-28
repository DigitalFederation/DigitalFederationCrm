<div wire:key="diving-locations-map-{{ $this->getId() }}" class="relative flex flex-col md:flex-row"
        {{-- Enhanced Alpine data with sidebar toggle for mobile --}} x-data="{
            isModalOpen: false,
            isSidebarOpen: window.innerWidth >= 768,
            closeSidebar() { this.isSidebarOpen = false; }
        }" x-init="window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                isSidebarOpen = true;
            }
        })"
        @open-diving-location-modal.window="isModalOpen = true" @close-diving-location-modal.window="isModalOpen = false"
        @keydown.escape.window="isModalOpen = false; $wire.call('closeModal')" x-cloak>

        {{-- Sidebar / Filters - Now with mobile toggle --}}
        <div x-show="isSidebarOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full" {{-- Simplified height/positioning, use flex for height --}}
            class="fixed inset-y-0 left-0 transform shadow-xl z-30 md:z-auto md:static md:transform-none w-full md:w-96 lg:w-96 flex-shrink-0 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col pt-16 md:pt-0">

            {{-- Sidebar Header with Brand - SIMPLIFIED --}}
            {{-- Simplified Header - Only Search and Close Button (mobile) --}}
            <div class="px-6 py-4 flex-shrink-0 border-b border-gray-200 dark:border-gray-700 bg-white">
                {{-- Search Box in Header --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="search" wire:model.live.debounce.500ms="searchTerm"
                        placeholder="{{ __('public.diving_locations.search_placeholder') }}"
                        class="pl-10 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 shadow-sm py-2.5 text-sm">
                    {{-- Updated search input style --}}
                </div>
            </div>

            {{-- Sidebar Content Area - Remove fixed height/overflow, main sidebar div handles scroll --}}
            <div class="px-6 py-4 bg-white dark:bg-gray-900 flex-1 overflow-y-auto">
                {{-- Stats Panel --}}
                <div class="flex space-x-3 mb-6">
                    <div class="flex-1 bg-white dark:bg-gray-700 rounded-xl overflow-hidden shadow-sm">
                        <div
                            class="px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 border-b border-blue-100 dark:border-gray-600">
                            <p class="text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                {{ __('public.diving_locations.locations') }}</p>
                        </div>
                        <div class="px-4 py-3 flex justify-between items-center">
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $this->totalLocations }}
                            </p>
                            <button @click="$wire.call('resetMap')"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                {{ __('public.diving_locations.reset') }}
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('public.diving_locations.filters') }}</h3>
                        <button type="button" wire:click="clearFilters"
                            class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            {{ __('public.diving_locations.clear_all') }}
                        </button>
                    </div>

                    {{-- Add x-data for filter collapse state --}}
                    <div class="space-y-4" x-data="{ countryOpen: true, districtOpen: false, waterTypeOpen: false, diveTypeOpen: false, levelOpen: false, depthOpen: false }">
                        {{-- Country Filter --}}
                        <div class="bg-white dark:bg-gray-700 rounded-xl overflow-hidden shadow-sm">
                            {{-- Make header clickable to toggle --}}
                            <button type="button" @click="countryOpen = !countryOpen"
                                class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 border-b border-blue-100 dark:border-gray-600 flex justify-between items-center text-left">
                                <p
                                    class="text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    {{ __('public.diving_locations.country') }}
                                </p>
                                {{-- Chevron Icon --}}
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': countryOpen }" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {{-- Wrap content with x-show and x-collapse --}}
                            <div x-show="countryOpen" x-collapse class="p-3">
                                <select id="country" wire:model.live="selectedCountry"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-200 text-sm">
                                    <option value="">{{ __('public.diving_locations.all_countries') }}</option>
                                    @foreach ($this->countries as $country)
                                        <option value="{{ $country->id }}"
                                            @if ($country->id == $selectedCountry) selected @endif>
                                            {{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- District Filter --}}
                        @if ($this->districts->isNotEmpty())
                        <div class="bg-white dark:bg-gray-700 rounded-xl overflow-hidden shadow-sm">
                            <button type="button" @click="districtOpen = !districtOpen"
                                class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 border-b border-blue-100 dark:border-gray-600 flex justify-between items-center text-left">
                                <p
                                    class="text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    {{ __('public.diving_locations.district') }}
                                </p>
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': districtOpen }" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="districtOpen" x-collapse class="p-3">
                                <select id="district" wire:model.live="selectedDistrict"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-200 text-sm">
                                    <option value="">{{ __('public.diving_locations.all_districts') }}</option>
                                    @foreach ($this->districts as $district)
                                        <option value="{{ $district->id }}"
                                            @if ($district->id == $selectedDistrict) selected @endif>
                                            {{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        {{-- Water Type Filter --}}
                        <div class="bg-white dark:bg-gray-700 rounded-xl overflow-hidden shadow-sm">
                            {{-- Make header clickable to toggle --}}
                            <button type="button" @click="waterTypeOpen = !waterTypeOpen"
                                class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 border-b border-blue-100 dark:border-gray-600 flex justify-between items-center text-left">
                                <p
                                    class="text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    {{ __('public.diving_locations.water_type') }}
                                </p>
                                {{-- Chevron Icon --}}
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': waterTypeOpen }" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {{-- Wrap content with x-show and x-collapse --}}
                            <div x-show="waterTypeOpen" x-collapse class="p-3 space-y-2">
                                @foreach ($waterTypeOptions as $type)
                                    @php
                                        $translationKey = match($type) {
                                            'Salt Water' => 'public.diving_locations.salt_water',
                                            'Fresh Water' => 'public.diving_locations.fresh_water',
                                            'Brackish Water' => 'public.diving_locations.brackish_water',
                                            default => $type,
                                        };
                                    @endphp
                                    <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <input type="checkbox" wire:model.live="selectedWaterTypes"
                                            value="{{ $type }}"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:checked:bg-blue-500 dark:checked:border-blue-500">
                                        <span class="ml-2">{{ __($translationKey) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Level Filter --}}
                        <div class="bg-white dark:bg-gray-700 rounded-xl overflow-hidden shadow-sm">
                            {{-- Make header clickable to toggle --}}
                            <button type="button" @click="levelOpen = !levelOpen"
                                class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 border-b border-blue-100 dark:border-gray-600 flex justify-between items-center text-left">
                                <p
                                    class="text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    {{ __('public.diving_locations.level') }}
                                </p>
                                {{-- Chevron Icon --}}
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': levelOpen }" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {{-- Wrap content with x-show and x-collapse --}}
                            <div x-show="levelOpen" x-collapse class="p-3 space-y-2">
                                @foreach ($levelOptions as $level)
                                    @php
                                        $translationKey = match($level) {
                                            'Beginner' => 'public.diving_locations.beginner',
                                            'Intermediate' => 'public.diving_locations.intermediate',
                                            'Advanced' => 'public.diving_locations.advanced',
                                            'Technical' => 'public.diving_locations.technical',
                                            default => $level,
                                        };
                                    @endphp
                                    <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <input type="checkbox" wire:model.live="selectedLevels"
                                            value="{{ $level }}"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:checked:bg-blue-500 dark:checked:border-blue-500">
                                        <span class="ml-2">{{ __($translationKey) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Dive Type Filter --}}
                        <div class="bg-white dark:bg-gray-700 rounded-xl overflow-hidden shadow-sm">
                            <button type="button" @click="diveTypeOpen = !diveTypeOpen"
                                class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 border-b border-blue-100 dark:border-gray-600 flex justify-between items-center text-left">
                                <p
                                    class="text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    {{ __('public.diving_locations.dive_type') }}
                                </p>
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': diveTypeOpen }" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="diveTypeOpen" x-collapse class="p-3 space-y-2">
                                @foreach ($diveTypeOptions as $diveType)
                                    @php
                                        $translationKey = match($diveType) {
                                            'Open Water' => 'public.diving_locations.open_water',
                                            'Inland Waters' => 'public.diving_locations.inland_waters',
                                            'Wall or Canyon' => 'public.diving_locations.wall_or_canyon',
                                            'Grotto' => 'public.diving_locations.grotto',
                                            'Cave' => 'public.diving_locations.cave',
                                            'Wreck' => 'public.diving_locations.wreck',
                                            'Pool' => 'public.diving_locations.pool',
                                            default => $diveType,
                                        };
                                    @endphp
                                    <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <input type="checkbox" wire:model.live="selectedDiveTypes"
                                            value="{{ $diveType }}"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:checked:bg-blue-500 dark:checked:border-blue-500">
                                        <span class="ml-2">{{ __($translationKey) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Depth Filter --}}
                        <div class="bg-white dark:bg-gray-700 rounded-xl overflow-hidden shadow-sm">
                            {{-- Make header clickable to toggle --}}
                            <button type="button" @click="depthOpen = !depthOpen"
                                class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 border-b border-blue-100 dark:border-gray-600 flex justify-between items-center text-left">
                                <p
                                    class="text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    {{ __('public.diving_locations.depth') }}
                                </p>
                                {{-- Chevron Icon --}}
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': depthOpen }" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {{-- Wrap content with x-show and x-collapse --}}
                            <div x-show="depthOpen" x-collapse class="p-3">
                                <input type="text" wire:model.live.debounce.500ms="depthSearch"
                                    placeholder="{{ __('public.diving_locations.depth_placeholder') }}"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 shadow-sm py-2 text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Locations List Section --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Dive Locations') }}</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ trans_choice('{1} :count dive location|[2,*] :count dive locations', $this->totalLocations, ['count' => $this->totalLocations]) }}
                        </span>
                    </div>

                    <div class="rounded-xl overflow-hidden bg-white dark:bg-gray-700 shadow-sm">
                        <div class="divide-y divide-gray-100 dark:divide-gray-600">
                            @forelse ($this->sidebarList as $location)
                                <div wire:click="showDetails({{ $location['id'] }})"
                                    class="p-4 hover:bg-blue-50 dark:hover:bg-gray-600 cursor-pointer transition duration-150 ease-in-out">
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-3 overflow-hidden border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700">
                                            @isset($location['country_code'])
                                                <img src="https://flagcdn.com/w40/{{ strtolower($location['country_code']) }}.png"
                                                    alt="{{ $location['country'] ?? '' }} flag"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <span
                                                    class="text-gray-500 dark:text-gray-400 font-semibold text-sm">?</span>
                                            @endisset
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                {{ $location['name'] }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">
                                                {{ $location['region'] ? $location['region'] . ', ' : '' }}{{ $location['country'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center">
                                    <div
                                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                                        {{ __('No locations found') }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-xs mx-auto">
                                        {{ __('Try adjusting your search or filter criteria to find diving locations.') }}
                                    </p>
                                    <button @click="$wire.call('resetMap')"
                                        class="mt-4 inline-flex items-center px-3 py-2 border border-blue-300 dark:border-blue-700 text-xs leading-4 font-medium rounded-md text-blue-700 dark:text-blue-400 bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        {{ __('Reset Filters') }}
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Map Area --}}
        {{-- Simplified: flex-1 handles height relative to parent --}}
        <div class="flex-1 relative">
            {{-- Map container with unique ID and wire:ignore --}}
            <div wire:ignore id="map-{{ $this->getId() }}" class="w-full h-full z-0"></div>

            {{-- Loading Indicator --}}
            <div wire:loading wire:target="searchTerm, selectedCountry, updateCountry"
                class="absolute inset-0 bg-white bg-opacity-80 dark:bg-gray-800 dark:bg-opacity-80 backdrop-filter backdrop-blur-sm flex items-center justify-center z-10">
                <div class="flex flex-col items-center bg-white dark:bg-gray-700 rounded-lg p-6 shadow-xl">
                    <svg class="animate-spin h-10 w-10 text-blue-600 dark:text-blue-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span
                        class="mt-3 text-sm font-medium text-gray-900 dark:text-gray-200">{{ __('Loading locations...') }}</span>
                    <span
                        class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('This may take a moment') }}</span>
                </div>
            </div>

            {{-- Mobile Fab Buttons --}}
            <div class="md:hidden fixed right-4 bottom-8 z-30 flex flex-col space-y-3">
                {{-- Reset Map Button --}}
                <button @click="$wire.call('resetMap')"
                    class="bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 p-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>

                {{-- Toggle Fullscreen Map Button --}}
                <button @click="isSidebarOpen = !isSidebarOpen"
                    class="bg-blue-600 text-white p-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg x-show="isSidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                    </svg>
                    <svg x-show="!isSidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Modal controlled by root x-data --}}
        <div x-show="isModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0" style="display: none;">

            {{-- Click away to close --}}
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity pointer-events-auto"
                @click="isModalOpen = false; $wire.call('closeModal')"></div>

            {{-- MODAL CONTAINER: Added overflow-y-auto, adjusted max-h slightly --}}
            <div x-show="isModalOpen"
                class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl transform transition-all w-full max-w-md mx-auto pointer-events-auto overflow-hidden flex flex-col max-h-[85vh] overflow-y-auto">
                {{-- Use the new Livewire property for details --}}
                @if ($this->selectedItemDetailsForModal)
                    <div class="relative">
                        {{-- Header: Conditional based on image --}}
                        @if (isset($this->selectedItemDetailsForModal['image_url']) && $this->selectedItemDetailsForModal['image_url'])
                            {{-- Header with Image Banner --}}
                            <div
                                class="relative h-48 bg-gray-500 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                <img src="{{ $this->selectedItemDetailsForModal['image_url'] }}"
                                    alt="{{ $this->selectedItemDetailsForModal['name'] }} background"
                                    class="absolute inset-0 w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40"></div> {{-- Dark overlay --}}

                                {{-- Close Button (Over Image) --}}
                                <button type="button" @click="isModalOpen = false; $wire.call('closeModal')"
                                    class="absolute right-4 top-4 text-white bg-black bg-opacity-30 hover:bg-opacity-50 rounded-full p-1.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white z-20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            {{-- Original Header with Gradient (No Image) --}}
                            <div
                                class="relative bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-800 dark:to-blue-900 px-6 py-8">
                                {{-- Close Button (Inside Gradient Header) --}}
                                <button type="button" @click="isModalOpen = false; $wire.call('closeModal')"
                                    class="absolute right-4 top-4 text-white bg-black bg-opacity-20 hover:bg-opacity-30 rounded-full p-1 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                {{-- No large initial here, it's in the avatar below --}}
                            </div>

                            {{-- Location Avatar (Only show when no image) --}}
                            <div class="absolute bottom-0 left-6 transform translate-y-1/3">
                                <div
                                    class="w-14 h-14 bg-white dark:bg-gray-700 rounded-full flex items-center justify-center border-4 border-white dark:border-gray-800 shadow-lg overflow-hidden">
                                    <span class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                        {{ strtoupper(substr($this->selectedItemDetailsForModal['name'], 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Main Content Area - Keep pt-6 for consistent spacing below header --}}
                    <div class="px-6 pt-6 pb-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2" id="modal-title">
                            {{ $this->selectedItemDetailsForModal['name'] }}
                        </h3>

                        @if ($this->selectedItemDetailsForModal['native_name'])
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                {{ $this->selectedItemDetailsForModal['native_name'] }}
                            </p>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4 text-sm">
                            @if ($this->selectedItemDetailsForModal['region'])
                                <div>
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span
                                            class="text-gray-700 dark:text-gray-300 font-medium">{{ __('Region') }}</span>
                                    </div>
                                    <p class="mt-0 ml-7 text-gray-600 dark:text-gray-400">
                                        {{ $this->selectedItemDetailsForModal['region'] }}
                                    </p>
                                </div>
                            @endif

                            <div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                    </svg>
                                    <span
                                        class="text-gray-700 dark:text-gray-300 font-medium">{{ __('Country') }}</span>
                                </div>
                                <div class="mt-0 ml-7 flex items-center">
                                    @isset($this->selectedItemDetailsForModal['country_code'])
                                        <img src="https://flagcdn.com/w20/{{ strtolower($this->selectedItemDetailsForModal['country_code']) }}.png"
                                            alt="{{ $this->selectedItemDetailsForModal['country'] ?? '' }} flag"
                                            class="w-5 h-auto mr-2 rounded-sm border border-gray-200 dark:border-gray-600">
                                    @endisset
                                    <span class="text-gray-600 dark:text-gray-400">
                                        {{ $this->selectedItemDetailsForModal['country'] ?? __('N/A') }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    <span
                                        class="text-gray-700 dark:text-gray-300 font-medium">{{ __('public.diving_locations.coordinates') }}</span>
                                </div>
                                <p class="mt-0 ml-7 text-gray-600 dark:text-gray-400 font-mono">
                                    {{ number_format($this->selectedItemDetailsForModal['lat'], 5) }},
                                    {{ number_format($this->selectedItemDetailsForModal['lng'], 5) }}
                                </p>
                            </div>

                            @isset($this->selectedItemDetailsForModal['depth'])
                                <div>
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                        </svg>
                                        <span
                                            class="text-gray-700 dark:text-gray-300 font-medium">{{ __('public.diving_locations.depth') }}</span>
                                    </div>
                                    <p class="mt-0 ml-7 text-gray-600 dark:text-gray-400">
                                        {{ $this->selectedItemDetailsForModal['depth'] }}
                                    </p>
                                </div>
                            @endisset

                            @isset($this->selectedItemDetailsForModal['water_type'])
                                <div>
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            {{-- Example: Cloud Upload (replace with better water icon if needed) --}}
                                        </svg>
                                        <span
                                            class="text-gray-700 dark:text-gray-300 font-medium">{{ __('public.diving_locations.water_type') }}</span>
                                    </div>
                                    <p class="mt-0 ml-7 text-gray-600 dark:text-gray-400">
                                        {{ __($this->selectedItemDetailsForModal['water_type']) }}
                                    </p>
                                </div>
                            @endisset

                            @isset($this->selectedItemDetailsForModal['level'])
                                <div>
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 6l3 6h12l3-6H3zm14 12H7a1 1 0 01-1-1v-3h12v3a1 1 0 01-1 1zM5 12h14" />
                                            {{-- Example: Layer group (replace with better level icon) --}}
                                        </svg>
                                        <span
                                            class="text-gray-700 dark:text-gray-300 font-medium">{{ __('public.diving_locations.level') }}</span>
                                    </div>
                                    <p class="mt-0 ml-7 text-gray-600 dark:text-gray-400">
                                        {{ $this->selectedItemDetailsForModal['level'] }}
                                    </p>
                                </div>
                            @endisset

                            @isset($this->selectedItemDetailsForModal['dive_type'])
                                <div>
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                        </svg>
                                        <span
                                            class="text-gray-700 dark:text-gray-300 font-medium">{{ __('public.diving_locations.dive_type') }}</span>
                                    </div>
                                    <p class="mt-0 ml-7 text-gray-600 dark:text-gray-400">
                                        {{ $this->selectedItemDetailsForModal['dive_type'] }}
                                    </p>
                                </div>
                            @endisset

                            @isset($this->selectedItemDetailsForModal['owner_name'])
                                <div>
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.034C12.425 5.526 12.885 5.072 13.38 4.675A18.023 18.023 0 008.558 1.079a18.023 18.023 0 00-3.899 3.246c.018.03.033.061.052.092 1.165 1.348 2.616 2.03 4.068 2.174.422.034.85.013 1.274-.039 1.802-.203 2.847-1.142 2.847-2.197 0-1.088-1.377-1.168-1.417-1.168-.04-.001-.081.004-.121.011a1.26 1.26 0 01-.715-.306 1.125 1.125 0 01-.306-.713c-.062-1.7.509-2.692 1.321-3.274.78-.56 1.83-.737 2.956-.418 1.165.337 2.02 1.045 2.33 1.952.745 2.05 1.016 4.077.84 6.135.465 1.44.367 2.467.314 2.659z" />
                                        </svg>
                                        <span
                                            class="text-gray-700 dark:text-gray-300 font-medium">{{ __('public.diving_locations.owner') }}</span>
                                    </div>
                                    <p class="mt-0 ml-7 text-gray-600 dark:text-gray-400">
                                        {{ $this->selectedItemDetailsForModal['owner_name'] }}
                                    </p>
                                </div>
                            @endisset
                        </div>

                        {{-- Notes Section --}}
                        @isset($this->selectedItemDetailsForModal['notes'])
                            @if (!empty(trim($this->selectedItemDetailsForModal['notes'])))
                                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        {{ __('public.diving_locations.description') }}
                                    </h4>
                                    <div
                                        class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                                        {!! $this->selectedItemDetailsForModal['notes'] !!}
                                    </div>
                                </div>
                            @endif
                        @endisset

                        @if (!empty($nearbyEntities))
                            <div class="mt-4 border-t border-gray-200 pt-3">
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">
                                    {{ __('public.diving_locations.nearby_dive_centers') }}</h4>
                                <ul class="space-y-2">
                                    @foreach ($nearbyEntities as $entity)
                                        <li
                                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150 ease-in-out">
                                            {{-- Entity Name with Link to Public Profile --}}
                                            <a href="{{ route('public.entity.show', $entity['id']) }}"
                                                class="block">
                                                <h5
                                                    class="font-semibold text-gray-700 dark:text-gray-200 text-base mb-1 hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $entity['name'] }}
                                                </h5>
                                            </a>

                                            <div
                                                class="flex items-start text-sm text-gray-600 dark:text-gray-400 mb-0.5">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1.5 flex-shrink-0 text-gray-400 dark:text-gray-500 mt-0.5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.05 12.95a7 7 0 119.9 0L10 17.9l-4.95-4.95zM10 9a1 1 0 100-2 1 1 0 000 2z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span>{{ $entity['address'] }}{{ $entity['address'] && $entity['location'] ? ', ' : '' }}{{ $entity['location'] }}{{ ($entity['address'] || $entity['location']) && $entity['postal_code'] ? ', ' : '' }}{{ $entity['postal_code'] }}{{ ($entity['address'] || $entity['location'] || $entity['postal_code']) && $entity['country'] ? ', ' : '' }}{{ $entity['country'] }}</span>
                                            </div>
                                            <div class="mt-2 flex flex-wrap gap-x-3 gap-y-1 text-xs">
                                                {{-- Visit Page Button --}}
                                                <a href="{{ route('public.entity.show', $entity['id']) }}"
                                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View Details
                                                </a>

                                                @if ($entity['website'])
                                                    <a href="{{ Str::startsWith($entity['website'], ['http://', 'https://']) ? $entity['website'] : 'http://' . $entity['website'] }}"
                                                        target="_blank" rel="noopener noreferrer"
                                                        class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-3.5 w-3.5 mr-1" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                        </svg>
                                                        Website
                                                    </a>
                                                @endif
                                                @if ($entity['email'])
                                                    <a href="mailto:{{ $entity['email'] }}"
                                                        class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-3.5 w-3.5 mr-1" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                        Email
                                                    </a>
                                                @endif
                                                @if ($entity['phone'])
                                                    <a href="tel:{{ $entity['phone'] }}"
                                                        class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:underline">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-3.5 w-3.5 mr-1" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                        </svg>
                                                        {{ $entity['phone'] }}
                                                    </a>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="mt-4 border-t border-gray-200 pt-3">
                                <p class="text-sm text-gray-500">{{ __('public.diving_locations.no_nearby_entities') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center mb-3">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $this->selectedItemDetailsForModal['lat'] }},{{ $this->selectedItemDetailsForModal['lng'] }}"
                                target="_blank" rel="noopener noreferrer"
                                class="inline-flex flex-shrink-0 w-full justify-center items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('public.diving_locations.open_in_google_maps') }}
                            </a>
                        </div>
                        <div>
                            <p class="text-blue-400 font-bold mb-1">{{ __('public.diving_locations.share_this_location') }}</p>
                        </div>
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 whitespace-nowrap">
                            <div class="w-full sm:w-auto flex gap-2">
                                <button type="button"
                                    wire:click="copyLink({{ $this->selectedItemDetailsForModal['id'] }})"
                                    class="inline-flex flex-shrink-0 justify-center items-center px-2.5 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                    <span class="hidden sm:inline">{{ __('public.diving_locations.copy_link') }}</span>
                                    <span class="sm:hidden">{{ __('public.diving_locations.link') }}</span>
                                </button>
                                <button type="button"
                                    wire:click="shareViaWhatsApp({{ $this->selectedItemDetailsForModal['id'] }})"
                                    class="inline-flex flex-shrink-0 justify-center items-center px-2.5 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                        fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.1-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-0.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                                    </svg>
                                    <span class="hidden sm:inline">{{ __('WhatsApp') }}</span>
                                    <span class="sm:hidden">{{ __('WhatsApp') }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="mt-3 flex justify-end">
                            <button type="button" @click="isModalOpen = false; $wire.call('closeModal')"
                                class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                                {{ __('Close') }}
                            </button>
                        </div>
                    </div>
                @else
                    <div class="p-8 flex flex-col items-center justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center">
                            {{ __('Loading location details...') }}
                        </p>
                    </div>
                @endif
            </div>

            <div x-data="{ show: false, message: '', type: 'success' }"
                @show-notification.window=" show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000); "
                x-show="show" x-transition
                :class="{ 'bg-green-100 border-green-400 text-green-700': type === 'success', 'bg-red-100 border-red-400 text-red-700': type === 'error' }"
                class="fixed bottom-5 right-5 border px-4 py-3 rounded shadow-lg z-[100] text-sm" role="alert">
                <span class="block sm:inline" x-text="message"></span>
            </div>

        </div>

    </div>

@push('head-css')
        {{-- Leaflet & MarkerCluster CSS --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css" />
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.css" />
        <style>
            :root {
                --z-base: 0;
                --z-map: 10;
                --z-map-controls: 15;
                --z-sidebar: 20;
                --z-controls: 30;
                --z-modal: 60;
            }

            /* Global Overrides */
            * {
                -webkit-tap-highlight-color: transparent;
            }

            /* Map Container */
            #map-{{ $this->getId() }} {
                height: 100%;
                width: 100%;
                min-height: 100vh;
                z-index: var(--z-base);
                background-color: #f0f0f0;
            }

            /* Map container sizing */
            .map-container {
                height: 100vh;
                width: 100%;
                position: absolute;
                inset: 0;
                z-index: 0;
                background-color: white;
            }

            /* Make sure map is always placed at a suitable layer in the z-index stack */
            .leaflet-container {
                background-color: #f8fafc !important;
            }

            /* Map and sidebar positioning */
            .min-h-screen.flex {
                position: relative;
            }

            @media (min-width: 768px) {
                .map-container {
                    position: relative;
                }
            }

            /* Force parent to maintain height */
            .w-screen.h-screen {
                display: flex;
                flex-direction: column;
            }

            /* Ensure sidebar and map flex properly */
            .min-h-screen.md\:flex {
                display: flex;
                flex: 1;
                overflow: hidden;
            }

            /* Custom markers */
            .diving-marker {
                transition: transform 0.2s ease;
            }

            .diving-marker:hover {
                transform: scale(1.1);
                z-index: 1000;
            }

            .marker-icon {
                position: relative;
                width: 40px;
                height: 40px;
                /* Larger for better mobile touch target */
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                box-shadow: 0 4px 10px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -1px rgba(0, 0, 0, 0.1);
                border: 3px solid white;
                background-color: #0284c7;
                color: white;
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
            }

            .marker-icon::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                border-left: 8px solid transparent;
                border-right: 8px solid transparent;
                border-top: 10px solid currentColor;
                filter: drop-shadow(0 4px 3px rgba(0, 0, 0, 0.1));
            }

            .federation.marker-icon {
                background-color: #7c3aed;
            }

            .entity.marker-icon {
                background-color: #0891b2;
            }

            /* Custom tooltip */
            .custom-tooltip {
                background: white;
                border: none;
                border-radius: 12px;
                padding: 10px 14px;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                font-size: 14px;
                max-width: 250px;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            .custom-tooltip::before {
                border-top-color: white;
            }

            /* Dark mode tooltips */
            .dark .custom-tooltip {
                background: #374151;
                color: #f3f4f6;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.25), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
            }

            .dark .custom-tooltip::before {
                border-top-color: #374151;
            }

            /* Custom cluster styles */
            .marker-cluster {
                background-color: rgba(59, 130, 246, 0.1);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .marker-cluster div {
                background-color: rgba(59, 130, 246, 0.9);
                color: white;
                font-weight: bold;
                box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.5);
                font-family: system-ui, -apple-system, sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .marker-cluster-small {
                background-color: rgba(59, 130, 246, 0.1);
            }

            .marker-cluster-small div {
                background-color: rgba(59, 130, 246, 0.9);
            }

            .marker-cluster-medium {
                background-color: rgba(139, 92, 246, 0.1);
            }

            .marker-cluster-medium div {
                background-color: rgba(139, 92, 246, 0.9);
            }

            .marker-cluster-large {
                background-color: rgba(239, 68, 68, 0.1);
            }

            .marker-cluster-large div {
                background-color: rgba(239, 68, 68, 0.9);
            }

            /* Mobile optimizations */
            @media (max-width: 768px) {
                .marker-icon {
                    width: 44px;
                    height: 44px;
                }

                .custom-tooltip {
                    font-size: 16px;
                    padding: 12px 16px;
                    max-width: 260px;
                }

                /* Enlarged map controls for mobile */
                .leaflet-touch .leaflet-control-zoom a {
                    width: 36px;
                    height: 36px;
                    line-height: 36px;
                    border-radius: 4px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                }

                .leaflet-touch .leaflet-control-zoom {
                    border-radius: 8px;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                }

                /* Safe area bottom */
                .pb-safe {
                    padding-bottom: env(safe-area-inset-bottom, 1rem);
                }
            }

            /* Ensure Leaflet controls are visible in dark mode */
            .leaflet-control-zoom a,
            .leaflet-control-attribution a,
            .leaflet-control-layers-toggle {
                color: #333 !important;
            }

            .leaflet-control-zoom {
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24) !important;
            }

            .leaflet-control-attribution {
                background: rgba(255, 255, 255, 0.8) !important;
                color: #333 !important;
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                border-radius: 4px 0 0 0;
                font-size: 10px !important;
                padding: 2px 6px !important;
            }

            /* Animations */
            @keyframes pulse {
                0% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.1);
                }

                100% {
                    transform: scale(1);
                }
            }

            .animate-pulse-custom {
                animation: pulse 1.5s infinite;
            }

            /* Leaflet pane z-index */
            .leaflet-pane {
                z-index: var(--z-map) !important;
            }

            .leaflet-top,
            .leaflet-bottom {
                z-index: var(--z-map-controls) !important;
            }
        </style>
    @endpush

    @push('footer-scripts')
        {{-- Leaflet & MarkerCluster JS --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js"></script>

        <script>
            document.addEventListener('livewire:init', () => {
                // Get component ID from a data attribute on the map element
                const mapElementId = 'map-' + @json($this->getId());
                const mapElement = document.getElementById(mapElementId);

                if (!mapElement) {
                    console.error('Map Init Error: Element #' + mapElementId + ' not found.');
                    return;
                }
                const wireIdElement = mapElement.closest('[wire\\:id]');
                if (!wireIdElement) {
                    console.error('Map Init Error: Could not find parent Livewire component.');
                    return;
                }
                const componentId = wireIdElement.getAttribute('wire:id');

                if (mapElement._leaflet_id) {
                    console.warn('Map Init: Already initialized.');
                    // If already initialized, maybe just ensure event listeners are attached if needed?
                    // For now, we return to prevent re-initializing the map itself.
                    return;
                }

                // console.log('Map: Initializing inside livewire:init.'); // Less verbose logging

                // Detect mobile devices for different defaults
                const isMobile = window.innerWidth < 768;

                // Debounce function
                function debounce(func, wait) {
                    let timeout;
                    return function executedFunction(...args) {
                        const later = () => {
                            clearTimeout(timeout);
                            func(...args);
                        };
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                    };
                }

                // Add a small delay to ensure the container is fully rendered and sized
                setTimeout(() => {
                    // Configure map options based on device
                    const mapOptions = {
                        center: [20, 0],
                        zoom: isMobile ? 3 : 3,
                        minZoom: 2,
                        maxZoom: 18,
                        zoomControl: !isMobile, // We'll position it differently on mobile
                        scrollWheelZoom: true,
                        worldCopyJump: true,
                        preferCanvas: true,
                        maxBounds: [
                            [-90, -180],
                            [90, 180]
                        ],
                        maxBoundsViscosity: 1.0,
                        doubleClickZoom: true,
                        tap: true,
                        bounceAtZoomLimits: true,
                        closePopupOnClick: true,
                        inertia: true,
                        fadeAnimation: true,
                        zoomAnimation: true,
                        markerZoomAnimation: true
                    };

                    // Initialize the map with better default options
                    let map = L.map(mapElement, mapOptions);

                    // Add zoom control to top-right on mobile
                    if (isMobile) {
                        L.control.zoom({
                            position: 'topright'
                        }).addTo(map);
                    }

                    // Add the tile layer with retina detection
                    /* const isRetina = window.devicePixelRatio > 1.5;
                    const tileUrl = isRetina ?
                        'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}@2x.png' :
                        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                    */
                    // Use standard OSM URL for testing
                    const tileUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

                    L.tileLayer(tileUrl, {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        subdomains: 'abc',
                        maxZoom: 19,
                        detectRetina: true
                    }).addTo(map);

                    // Initialize marker cluster group with professional styling
                    const markerClusterGroup = L.markerClusterGroup({
                        maxClusterRadius: isMobile ? 60 : 50,
                        spiderfyOnMaxZoom: true,
                        showCoverageOnHover: false,
                        zoomToBoundsOnClick: true,
                        disableClusteringAtZoom: 14,
                        chunkedLoading: true,
                        animate: true,
                        animateAddingMarkers: true,
                        iconCreateFunction: function(cluster) {
                            const count = cluster.getChildCount();
                            let size, className;

                            if (count > 100) {
                                size = 'large';
                                className = 'marker-cluster-large';
                            } else if (count > 30) {
                                size = 'medium';
                                className = 'marker-cluster-medium';
                            } else {
                                size = 'small';
                                className = 'marker-cluster-small';
                            }

                            return L.divIcon({
                                html: `<div><span>${count}</span></div>`,
                                className: `marker-cluster ${className}`,
                                iconSize: L.point(40, 40)
                            });
                        }
                    });

                    // Add the cluster group to the map
                    map.addLayer(markerClusterGroup);

                    // Force a map resize for proper rendering
                    setTimeout(() => {
                        map.invalidateSize();

                        // Set initial view based on device
                        if (isMobile) {
                            map.setView([20, 0], 2);
                        } else {
                            map.setView([25, 10], 3);
                        }
                    }, 100);

                    let markers = {};
                    let highlightedMarker = null;
                    let lastZoom = map.getZoom();

                    // Track loading state for smoother UX
                    const setLoading = (isLoading) => {
                        const loadingIndicator = document.querySelector('[wire\\:loading]');
                        if (loadingIndicator) {
                            if (isLoading) {
                                loadingIndicator.style.display = 'flex';
                            } else {
                                setTimeout(() => {
                                    loadingIndicator.style.display = 'none';
                                }, 300);
                            }
                        }
                    };

                    // Function to create a custom marker with diving icon
                    function createDivingMarker(location) {
                        const ownerType = location.owner_type;
                        const markerClass = ownerType === 'Federation' ? 'federation' : 'entity';

                        // Create different sized icons based on zoom level
                        const icon = L.divIcon({
                            className: `diving-marker ${markerClass.toLowerCase()}`,
                            html: `<div class="marker-icon ${markerClass.toLowerCase()}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                    </svg>
                                  </div>`,
                            iconSize: [40, 40],
                            iconAnchor: [20, 40]
                        });

                        const marker = L.marker([location.lat, location.lng], {
                            icon,
                            id: location.id, // Store ID for later reference
                            alt: location.name,
                            title: location.name, // Show name on hover
                            riseOnHover: true
                        });

                        // Enhanced tooltip content
                        const name = location.name.length > 25 ? location.name.slice(0, 25) + "…" : location
                            .name;
                        const region = location.region ? location.region : '';
                        const country = location.country || '';

                        let tooltipContent = `<strong>${name}</strong>`;
                        if (region || country) {
                            tooltipContent +=
                                `<br><small>${region}${region && country ? ', ' : ''}${country}</small>`;
                        }

                        marker.bindTooltip(tooltipContent, {
                            direction: "top",
                            offset: [0, -36],
                            className: "custom-tooltip",
                            opacity: 0.95
                        });

                        return marker;
                    }

                    const updateMarkers = (event) => {
                        setLoading(true);

                        if (!map) {
                            setLoading(false);
                            return;
                        }

                        // In Livewire 3, the event payload is an object with named parameters
                        const locations = event.locations || [];

                        // Handle both array and object formats
                        const locationsArray = Array.isArray(locations) ? locations :
                            (typeof locations === 'object' ? Object.values(locations) : []);

                        // Clear existing markers
                        markerClusterGroup.clearLayers();
                        markers = {};
                        removeHighlight();

                        // Skip if no markers to add
                        if (locationsArray.length === 0) {
                            setLoading(false);
                            return;
                        }

                        // Add new markers to the cluster group
                        locationsArray.forEach(location => {
                            const marker = createDivingMarker(location);

                            marker.on('click', () => {
                                // Use Livewire.find() with explicit component ID
                                if (componentId) {
                                    Livewire.find(componentId).showDetails(location.id);

                                    // Close sidebar on mobile when location is selected
                                    if (window.innerWidth < 768) {
                                        // Find the Alpine component and call the closeSidebar method
                                        const rootEl = document.querySelector('[x-data]');
                                        if (rootEl && typeof Alpine !== 'undefined') {
                                            Alpine.evaluate(rootEl, 'closeSidebar()');
                                        }
                                    }
                                }
                            });

                            // Store the marker in our map by ID
                            markers[location.id] = marker;

                            // Add to cluster group instead of directly to map
                            markerClusterGroup.addLayer(marker);
                        });

                        // Force map update after adding markers
                        map.invalidateSize();

                        setLoading(false);

                        // Auto-fit map bounds if we have markers and aren't responding to a filter change
                        if (locationsArray.length > 0 && !event.isFilterChange && locationsArray.length <
                            100) {
                            const bounds = markerClusterGroup.getBounds();
                            if (bounds.isValid()) {
                                map.fitBounds(bounds, {
                                    padding: [50, 50],
                                    maxZoom: 12,
                                    animate: true
                                });
                            }
                        }
                    };

                    const resetMap = () => {
                        setLoading(true);

                        if (map) {
                            map.flyTo([20, 0], isMobile ? 2 : 3, {
                                duration: 1.5,
                                easeLinearity: 0.25
                            });
                            map.invalidateSize();
                        }

                        setTimeout(() => setLoading(false), 1500);
                    };

                    const centerMap = (detail) => {
                        setLoading(true);

                        if (map) {
                            map.flyTo([detail.lat, detail.lng], detail.zoom || (isMobile ? 10 : 12), {
                                duration: 1.5,
                                easeLinearity: 0.25
                            });
                            map.invalidateSize();
                        }

                        setTimeout(() => setLoading(false), 1500);
                    };

                    const highlightMarker = (detail) => {
                        removeHighlight();
                        const marker = markers[detail.id];
                        if (marker) {
                            highlightedMarker = marker;

                            // Ensure the marker is visible (not in a cluster)
                            markerClusterGroup.zoomToShowLayer(marker, function() {
                                const markerClass = marker.options.icon.options.className.split(
                                    ' ')[1];

                                marker.setIcon(L.divIcon({
                                    className: `diving-marker ${markerClass}`,
                                    html: `<div class="marker-icon ${markerClass} animate-pulse-custom" style="transform: scale(1.2); border-color: #FFDD3C;">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                          </div>`,
                                    iconSize: [44, 44],
                                    iconAnchor: [22, 44]
                                }));

                                // Open tooltip and keep it open
                                marker.openTooltip();

                                // Make sure map is correctly centered on marker
                                map.setView([detail.lat, detail.lng], map.getZoom(), {
                                    animate: true,
                                    duration: 0.5
                                });
                            });
                        }
                    };

                    const removeHighlight = () => {
                        if (highlightedMarker) {
                            // Reset to normal marker appearance
                            const markerClass = highlightedMarker.options.icon.options.className.split(' ')[
                                1];
                            highlightedMarker.setIcon(L.divIcon({
                                className: `diving-marker ${markerClass}`,
                                html: `<div class="marker-icon ${markerClass}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                        </svg>
                                      </div>`,
                                iconSize: [40, 40],
                                iconAnchor: [20, 40]
                            }));
                            highlightedMarker.closeTooltip();
                            highlightedMarker = null;
                        }
                    };

                    // Track zoom level changes to update marker sizes
                    map.on('zoomend', function() {
                        const currentZoom = map.getZoom();
                        // Only resize if zoom changed significantly
                        if (Math.abs(currentZoom - lastZoom) > 2) {
                            lastZoom = currentZoom;
                            // Refresh markers for current zoom level
                            Object.values(markers).forEach(marker => {
                                markerClusterGroup.refreshClusters(marker);
                            });
                        }
                    });

                    // Handle window resize events to ensure map properly redraws
                    window.addEventListener('resize', () => {
                        if (map) {
                            map.invalidateSize();
                        }
                    });

                    // --- Livewire Event Listeners ---
                    Livewire.on('updateLocations', updateMarkers);
                    Livewire.on('centerMap', centerMap);
                    Livewire.on('resetMap', resetMap);
                    Livewire.on('highlightMarker', highlightMarker);
                    Livewire.on('removeHighlight', removeHighlight);

                    // --- Browser Event Listeners for Sharing ---
                    window.addEventListener('copy-to-clipboard', event => {
                        const {
                            text
                        } = event.detail;
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(text).then(() => {
                                console.log('Link copied to clipboard');
                                // Notification is handled by Livewire's dispatch
                            }).catch(err => {
                                console.error('Failed to copy text: ', err);
                                // Optionally show an error notification via JS if Livewire one fails
                            });
                        } else {
                            console.warn('Clipboard API not available.');
                            // Fallback: maybe prompt user to copy manually
                        }
                    });

                    window.addEventListener('open-url', event => {
                        const {
                            url,
                            target
                        } = event.detail;
                        window.open(url, target || '_blank');
                    });

                    window.addEventListener('web-share', async event => {
                        const {
                            title,
                            text,
                            url
                        } = event.detail;
                        if (navigator.share) {
                            try {
                                await navigator.share({
                                    title,
                                    text,
                                    url
                                });
                                console.log('Content shared successfully');
                            } catch (err) {
                                console.error('Error sharing content:', err);
                                // Don't copy here if share fails, user might cancel
                                // Livewire already sent a notification if data prep failed.
                            }
                        } else {
                            console.warn('Web Share API not supported, copying link instead.');
                            // Fallback to copying the link
                            const fallbackText = `${title}: ${url}`;
                            const wireComponent = Livewire.find(componentId);
                            if (navigator.clipboard) {
                                navigator.clipboard.writeText(fallbackText).then(() => {
                                    // Show a JS notification *here* because the primary action failed
                                    // Use Livewire showNotification for consistency if possible
                                    if (wireComponent) {
                                        wireComponent.dispatch('showNotification', {
                                            message: 'Sharing not supported, link copied!',
                                            type: 'info'
                                        });
                                    } else {
                                        alert(
                                            'Sharing not supported, link copied to clipboard instead!'
                                        );
                                    }
                                }).catch(err => {
                                    console.error('Failed to copy fallback text: ', err);
                                });
                            } else {
                                alert(
                                    'Sharing and clipboard not supported. Please copy the link manually.'
                                );
                            }
                        }
                    });

                    // Initial update of the locations
                    if (componentId) {
                        const component = Livewire.find(componentId);
                        if (component) {
                            // Call the proper public method instead of lifecycle hook
                            component.call('initializeMap');
                        }
                    }

                    // --- Map Event Listener for Bounds Update ---
                    const sendBoundsToLivewire = debounce(() => {
                        const component = Livewire.find(componentId);
                        if (map && component) {
                            const bounds = map.getBounds();
                            if (bounds.isValid()) {
                                const ne = bounds.getNorthEast();
                                const sw = bounds.getSouthWest();
                                // Call the Livewire method only if filters are NOT active
                                // We check this via a direct call for simplicity, assumes a method exists
                                // Alternatively, manage a JS state variable synced with Livewire filter state
                                component.call('updateBounds', {
                                    ne: {
                                        lat: ne.lat,
                                        lng: ne.lng
                                    },
                                    sw: {
                                        lat: sw.lat,
                                        lng: sw.lng
                                    }
                                });
                            }
                        }
                    }, 750); // Debounce for 750ms

                    map.on('moveend', sendBoundsToLivewire);

                    // Initial bounds update after map loads
                    map.whenReady(sendBoundsToLivewire);

                }, 100); // End of main setTimeout
            }); // End of livewire:init
        </script>
    @endpush
