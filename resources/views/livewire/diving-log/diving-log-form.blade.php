<div xmlns:wire="http://www.w3.org/1999/xhtml">

    @if ($errors->any() || session('validationErrors'))
        <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
            <p class="font-bold">Please review the following issues</p>
            <ul class="list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
                @if (session('validationErrors'))
                    @foreach (session('validationErrors') as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                @endif
            </ul>
        </div>
    @endif

    <!-- Progress Steps - Enhanced for better mobile experience -->
    <div class="flex items-center justify-between mt-4 mb-6 px-2">
        @foreach(range(1, 4) as $step)
            <div class="flex flex-col items-center flex-1">
                <button
                    class="relative w-8 h-8 rounded-full flex items-center justify-center mb-2
                        {{ $formStep == $step ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}
                        {{ $formStep > $step ? 'bg-green-500 text-white' : '' }}"
                    wire:click="goToStep({{ $step }})"
                    {{ $formStep == $step ? 'disabled' : '' }}
                >
                    @if($formStep > $step)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        {{ $step }}
                    @endif
                </button>
                <span class="text-xs hidden md:block {{ $formStep == $step ? 'text-blue-600 font-medium' : 'text-gray-500' }}">
                    @switch($step)
                        @case(1) Basic Info @break
                        @case(2) Environment @break
                        @case(3) Dive Data @break
                        @case(4) Notes @break
                    @endswitch
                </span>
                @if($step < 4)
                    <div class="hidden md:block absolute w-full" style="left: 50%; height: 2px; top: 1rem; z-index: -1">
                        <div class="{{ $formStep > $step ? 'bg-green-500' : 'bg-gray-200' }} h-full"></div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <form wire:submit="saveAsComplete">


        @if($formStep === 1)
            <section class="card" id="formFirstStep">

                @if($isFirstDive && !empty($divingLogArray['dive_type']))
                    <section class="border-b border-slate-200 pb-6 mb-6">
                        <div class="flex flex-col gap-y-4">
                            <!-- Info box moved to top for better context -->
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <h4 class="font-semibold text-blue-800 mb-1">First dive!</h4>
                                <p class="text-sm text-blue-600">Since this is your first dive registration, please choose your starting sequence number. This cannot be changed later.</p>
                            </div>

                            <!-- Input field below info box -->
                            <div class="w-full">
                                <label class="block mb-1 font-medium">Dive Sequence Number</label>
                                <input
                                    type="number"
                                    name="dive_sequence"
                                    class="form-input w-full text-lg"
                                    wire:model="divingLogArray.dive_sequence_number"
                                    min="1"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                >
                                @error('divingLogArray.dive_sequence_number')
                                    <span class="text-xs text-red-500 font-medium block mt-1">{{ $message }}</span>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">This number will be the starting point for your dive count</p>
                            </div>
                        </div>
                    </section>
                @endif


                <div class="flex flex-col md:flex-row md:gap-x-4 gap-y-4">

                    <!-- Dive Type selection -->
                    <div class="w-full md:w-1/3">
                        <label class="font-medium text-gray-700">
                            Dive Type
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            wire:model.live="divingLogArray.dive_type"
                            class="choices form-select w-full"
                        >
                            <option value="">{{ __('Select dive type') }}</option>
                            @foreach ($this->getDiveTypes() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>


                        @error('divingLogArray.dive_type')
                            <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                        @enderror
                        <p class="text-xs text-gray-400">Select the type of dive you are registering</p>
                    </div>

                    <!-- Dive Category -->
                    <div class="w-full md:w-1/3">
                        <label>Dive Category</label>
                        <select wire:model="divingLogArray.category" class="form-select w-full">
                            <option value="">{{ __('Select dive category') }}</option>
                            @foreach ($this->getDiveCategoryOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('divingLogArray.dive_category') <span
                            class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-400">Select the category of dive you are registrating</p>
                    </div>

                    <!-- Dive Date -->
                    <div class="w-full md:w-1/3">

                        <label>Dive Date and Time</label>
                        <input wire:model="divingLogArray.date_and_time" type="datetime-local"
                               class="form-input w-full">
                        @error('divingLogArray.date_and_time') <span
                            class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-400">Enter the date and time of the dive. </p>
                    </div>

                </div>

                <div class="flex flex-col gap-y-4 md:mt-4">


                    <!-- Dive Location -->
                    <div>

                        <div class="w-full mt-4">
                            <label>Dive Location</label>
                            <p class="text-xs text-gray-400">Choose from the map or list bellow the location of your
                                dive. If the place doesn't exist, <a
                                    href="{{ route('individual.diving-location.create')}}">click here to create one</a>.
                            </p>
                        </div>

                        <livewire:select-location-component
                            :location_id="$divingLogArray['diving_location_id'] ?? null"
                            :location="$location" />

                    </div>


                    <!-- Next button -->
                    <div class="flex justify-end">
                        <button type="button" wire:click="goToStep(2)" class="btn btn-action w-full">Next</button>
                    </div>


                </div>


            </section>
        @endif


        @if($formStep === 2)
            <section class="card" id="formSecondStep">
                <h2 class="font-bold">Environment</h2>
                <div class="mt-4 flex flex-col gap-y-4">

                    <div>
                        <label>Entry</label>
                        <select wire:model="divingLogArray.environment_entry" class="form-select w-full">
                            <option value="">{{ __('Select option') }}</option>
                            @foreach ($this->getEnvironmentEntryOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div>
                        <label>Water Type</label>
                        <select wire:model="divingLogArray.environment_water_type" class="form-select w-full">
                            <option value="">{{ __('Select option') }}</option>
                            @foreach ($this->getEnvironmentWaterTypeOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div>
                        <label>Current</label>
                        <select wire:model="divingLogArray.environment_current" class="form-select w-full">
                            <option value="">{{ __('Select option') }}</option>
                            @foreach ($this->getEnvironmentCurrentOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Surface</label>
                        <select wire:model="divingLogArray.environment_surface" class="form-select w-full">
                            <option value="">{{ __('Select option') }}</option>
                            @foreach ($this->getEnvironmentSurfaceOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="flex flex-col md:flex-row items-center gap-x-2 gap-y-4">

                        <div class="flex flex-col w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Water Temperature') }}
                            </label>
                            <div class="form-input-group relative rounded-lg shadow-sm">
                                <input
                                    wire:model="divingLogArray.environment_water_temperature"
                                    class="form-input w-full pl-4 pr-20 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    placeholder="0000"
                                    minlength="0"
                                    maxlength="4"
                                    inputmode="numeric"
                                >
                                <select
                                    wire:model="divingLogArray.environment_water_temperature_unit"
                                    class="absolute right-0 top-0 bottom-0 w-16 border-l border-gray-300 rounded-r-lg bg-gray-50 text-sm"
                                >
                                    @foreach ($this->getTemperatureUnitOptions() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="flex flex-col w-full">
                            <label>{{ __('Air Temperature') }}</label>
                            <div class="form-input-group">
                                <input wire:model="divingLogArray.environment_air_temperature"
                                       class="form-input numeric border-none bg-transparent outline-none"
                                       placeholder="0000" minlength="0" maxlength="4">
                                <select wire:model="divingLogArray.environment_air_temperature_unit"
                                        class="form-input form-select-append">
                                    @foreach ($this->getTemperatureUnitOptions() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('divingLogArray.environment_air_temperature') <span
                                class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                        </div>


                        <div class="flex flex-col w-full">
                            <label>{{ __('Water Visibility') }}</label>
                            <div class="form-input-group">
                                <input wire:model="divingLogArray.environment_water_visibility"
                                       class="form-input numeric border-none bg-transparent outline-none"
                                       placeholder="0000" minlength="0" maxlength="4">
                                <select wire:model="divingLogArray.environment_water_visibility_unit"
                                        class="form-input form-select-append">
                                    @foreach ($this->getDistanceUnitOptions() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('divingLogArray.environment_water_visibility') <span
                                class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                        </div>

                    </div>


                    <div>
                        <label>Wildlife</label>
                        <textarea wire:model="divingLogArray.wildlife" class="form-textarea w-full"
                                  placeholder="Describe the wildlife you saw"></textarea>
                    </div>


                    <div class="flex justify-end">
                        <button type="button" wire:click="goToStep(3)" class="btn btn-action w-full">Next</button>
                    </div>


                </div>
            </section>
        @endif


        @if($formStep === 3)

            @include('livewire.diving-log.partials.diving-data')

            @include('livewire.diving-log.partials.free-diving-data')

            @include('livewire.diving-log.partials.extended-range')

            @include('livewire.diving-log.partials.rebreather-ccr')

            @include('livewire.diving-log.partials.rebreather-scr')

        @endif


        @if($formStep ===4)
            <section class="card" id="formFourthStep">
                <div>
                    <label>Notes</label>
                    <textarea wire:model="divingLogArray.notes" class="form-textarea w-full"
                              placeholder="Add any additional notes about the dive"></textarea>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn w-full btn-action">Save diving log</button>
                </div>
            </section>
        @endif

    </form>


    @push('footer-scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                document.body.addEventListener("keyup", event => {
                    if (event.target.classList.contains("numeric")) {
                        var value = event.target.value;
                        var regex = /^\d+$/;
                        if (!regex.test(value)) {
                            event.target.value = value.replace(/[^0-9\.]/g, "");
                        }
                    }
                });
            });
        </script>
    @endpush
</div>
