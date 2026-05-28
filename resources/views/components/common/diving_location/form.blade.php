@props([
    'countries' => [],
    'districts' => [],
    'divingLocation' => null,
    'formMethod' => 'POST',
    'formAction' => '',
    'existingLocations' => [],
    'publicLocations' => [],
])

<div class="max-w-6xl mx-auto">
    <!-- Page header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $formMethod == 'POST' ? __('diving_location.new_dive_location') : __('diving_location.edit_dive_location') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $formMethod == 'POST' ? __('diving_location.create_description') : __('diving_location.edit_description') }}
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method($formMethod)

        <!-- Map Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('diving_location.location_on_map') }}</h2>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('diving_location.click_inside_map') }}</p>
            </div>
            <livewire:map-location-component :divingLocation="$divingLocation" :existingLocations="$existingLocations" :publicLocations="$publicLocations" />
        </div>

        <!-- Basic Information Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('diving_location.basic_information') }}</h2>
                </div>
            </div>
            <div class="p-5 space-y-5">
                <!-- Row 1: Country, District -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="country">
                            {{ __('diving_location.country') }} <span class="text-rose-500">*</span>
                        </label>
                        <select name="country_id" id="country"
                            class="form-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('country_id') ? 'border-rose-300' : '' }}"
                            required>
                            <option value="" selected disabled>{{ __('diving_location.select_option') }}</option>
                            @foreach ($countries as $country_id => $country)
                                <option value="{{ $country_id }}" @if (old('country_id', $divingLocation->country_id) == $country_id) selected @endif>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="district_id">
                            {{ __('diving_location.district') }}
                        </label>
                        <select name="district_id" id="district_id"
                            class="form-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('district_id') ? 'border-rose-300' : '' }}">
                            <option value="">{{ __('diving_location.select_option') }}</option>
                            @foreach ($districts as $district_id => $district_name)
                                <option value="{{ $district_id }}" @if (old('district_id', $divingLocation->district_id ?? '') == $district_id) selected @endif>
                                    {{ $district_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district_id')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Native Name, International Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="native_name">
                            {{ __('diving_location.native_name') }}
                        </label>
                        <input id="native_name" type="text" name="native_name"
                            class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('native_name') ? 'border-rose-300' : '' }}"
                            value="{{ old('native_name', $divingLocation->native_name ?? '') }}"
                            placeholder="{{ __('diving_location.native_name_placeholder') }}" />
                        @error('native_name')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="name">
                            {{ __('diving_location.international_name') }}
                        </label>
                        <input id="name" type="text" name="name"
                            class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('name') ? 'border-rose-300' : '' }}"
                            value="{{ old('name', $divingLocation->name ?? '') }}"
                            placeholder="{{ __('diving_location.international_name_placeholder') }}" />
                        @error('name')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Region, Latitude, Longitude -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="region">
                            {{ __('diving_location.region') }}
                        </label>
                        <input id="region" type="text" name="region"
                            class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('region') ? 'border-rose-300' : '' }}"
                            value="{{ old('region', $divingLocation->region ?? '') }}" />
                        @error('region')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="latitude">
                            {{ __('diving_location.latitude') }}
                        </label>
                        <input id="latitudeInput" type="text" name="lat"
                            class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 bg-gray-50 dark:bg-gray-600 {{ $errors->has('lat') ? 'border-rose-300' : '' }}"
                            value="{{ old('lat', $divingLocation->lat ?? '') }}" readonly />
                        @error('lat')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="longitude">
                            {{ __('diving_location.longitude') }}
                        </label>
                        <input id="longitudeInput" type="text" name="lng"
                            class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 bg-gray-50 dark:bg-gray-600 {{ $errors->has('lng') ? 'border-rose-300' : '' }}"
                            value="{{ old('lng', $divingLocation->lng ?? '') }}" readonly />
                        @error('lng')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Dive Characteristics Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('diving_location.dive_characteristics') }}</h2>
                </div>
            </div>
            <div class="p-5 space-y-6">
                <!-- Row: Depth and Water Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="depth">
                            {{ __('diving_location.depth') }}
                        </label>
                        <div class="relative">
                            <input id="depth" type="text" name="depth"
                                class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 pr-12 {{ $errors->has('depth') ? 'border-rose-300' : '' }}"
                                value="{{ old('depth', $divingLocation->depth ?? '') }}"
                                placeholder="{{ __('diving_location.depth_placeholder') }}" />
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">m</span>
                        </div>
                        @error('depth')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="water_type">
                            {{ __('diving_location.water_type') }}
                        </label>
                        <select name="water_type" id="water_type"
                            class="form-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('water_type') ? 'border-rose-300' : '' }}">
                            <option value="">{{ __('diving_location.select_option') }}</option>
                            <option value="Salt Water" @if (old('water_type', $divingLocation->water_type) == 'Salt Water') selected @endif>
                                {{ __('diving_location.salt_water') }}
                            </option>
                            <option value="Fresh Water" @if (old('water_type', $divingLocation->water_type) == 'Fresh Water') selected @endif>
                                {{ __('diving_location.fresh_water') }}
                            </option>
                            <option value="Brackish Water" @if (old('water_type', $divingLocation->water_type) == 'Brackish Water') selected @endif>
                                {{ __('diving_location.brackish_water') }}
                            </option>
                        </select>
                        @error('water_type')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Level Selection - Multi Checkbox -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('diving_location.level') }}
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ __('diving_location.level_help') }}</p>
                    @php
                        $selectedLevels = old('level', $divingLocation->level ?? []) ?? [];
                        if (is_string($selectedLevels)) {
                            $selectedLevels = [$selectedLevels];
                        }
                        $levelNumbers = [
                            'Beginner' => '1',
                            'Intermediate' => '2',
                            'Advanced' => '3',
                            'Technical' => '4',
                        ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach(\Domain\DivingLogs\Models\DivingLocation::LEVELS as $value => $label)
                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                <input type="checkbox" name="level[]" value="{{ $value }}"
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-700"
                                    @if(in_array($value, $selectedLevels)) checked @endif>
                                <div class="flex items-center gap-2">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 text-sm font-bold">
                                        {{ $levelNumbers[$value] ?? '?' }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('diving_location.' . strtolower($label)) }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('level')
                        <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dive Type Selection - Multi Checkbox -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('diving_location.dive_type') }}
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ __('diving_location.dive_type_help') }}</p>
                    @php
                        $selectedDiveTypes = old('dive_type', $divingLocation->dive_type ?? []) ?? [];
                        if (is_string($selectedDiveTypes)) {
                            $selectedDiveTypes = [$selectedDiveTypes];
                        }
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach(\Domain\DivingLogs\Models\DivingLocation::DIVE_TYPES as $value => $label)
                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                <input type="checkbox" name="dive_type[]" value="{{ $value }}"
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-700"
                                    @if(in_array($value, $selectedDiveTypes)) checked @endif>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('diving_location.' . str_replace(' ', '_', strtolower($label))) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('dive_type')
                        <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Media & Notes Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('diving_location.media_and_notes') }}</h2>
                </div>
            </div>
            <div class="p-5 space-y-5">
                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('diving_location.image') }}
                    </label>
                    <div class="flex items-center gap-4">
                        @if ($formMethod == 'PUT' && $divingLocation && $divingLocation->hasMedia('diving-location-images'))
                            <div class="relative group">
                                <img src="{{ $divingLocation->getFirstMediaUrl('diving-location-images', 'thumb') }}"
                                    alt="{{ $divingLocation->name }}"
                                    class="w-24 h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                                <label class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg cursor-pointer">
                                    <input type="checkbox" name="remove_image" class="sr-only peer">
                                    <span class="text-white text-xs font-medium peer-checked:hidden">{{ __('diving_location.click_to_remove') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white hidden peer-checked:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </label>
                            </div>
                        @endif
                        <div class="flex-1">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-blue-400 dark:hover:border-blue-500 transition-colors bg-gray-50 dark:bg-gray-700/50">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-medium text-blue-600 dark:text-blue-400">{{ __('diving_location.click_to_upload') }}</span>
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">PNG, JPG, WEBP (max. 2MB)</p>
                                </div>
                                <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp" class="hidden" />
                            </label>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="notes">
                        {{ __('diving_location.notes') }}
                    </label>
                    <x-forms.tinymce-editor-static name="notes" elementId="notes" value="{{ old('notes', $divingLocation->notes ?? '') }}" />
                    @error('notes')
                        <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route(Request::segment(1) . '.diving-location.index') }}"
                class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                {{ __('diving_location.back') }}
            </a>
            <button type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                {{ __('diving_location.save_record') }}
            </button>
        </div>
    </form>
</div>

@push('footer-scripts')
    <script>
        document.getElementById('country').addEventListener('change', (e) => {
            const countryId = e.target.value;
            window.livewire.emit('countryChanged', countryId);
        });
    </script>
@endpush
