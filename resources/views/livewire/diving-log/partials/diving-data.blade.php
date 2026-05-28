@if($divingLogArray['dive_type'] == 1)

    <section id="formThirdStep">

        <div class="card">
            <h2 class="font-bold">Diving</h2>

            <div class="mt-4 flex flex-col gap-y-4">

                <!-- Entry, Speciality Dive, Duration Minutes -->
                <div>
                    <label>Speciality Dive</label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-1 md:gap-4 mt-2">
                        @foreach ($this->getSpecialityDiveOptions() as $value => $label)
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" class="form-checkbox" wire:model="divingLogArray.divingData.speciality_dive.{{ $value }}" value="{{ $value }}">
                                    <span class="ml-2">{{ $label }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label>Duration</label>
                    <input wire:model="divingLogArray.divingData.duration_minutes" class="numeric w-full form-input" placeholder="0000 minutes" minlength="0" maxlength="4">
                    @error('divingLogArray.divingData.duration_minutes') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <!-- Depth, Depth Unit, Nitrox Percentage -->
                <div class="flex flex-col">
                    <label>Depth</label>
                    <div class="form-input-group">
                        <input wire:model="divingLogArray.divingData.depth" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                        <select wire:model="divingLogArray.divingData.depth_unit" class="form-input form-select-append">
                            @foreach ($this->getDistanceUnitOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label>Nitrox</label>
                    <input wire:model="divingLogArray.divingData.nitrox_percentage" class="numeric form-input w-full" placeholder="000 %" minlength="0" maxlength="3">
                    @error('divingLogArray.divingData.nitrox_percentage') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>


                <!-- Tank Type, Tank Volume, Tank Volume Unit -->

                <div>
                    <label>Tank Type</label>
                    <select wire:model="divingLogArray.divingData.tank_type" class="form-select w-full">
                        @foreach ($this->getTankTypeOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('divingLogArray.divingData.tank_type') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col">
                    <label>Tank Volume</label>
                    <div class="form-input-group">
                        <input wire:model="divingLogArray.divingData.tank_volume" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                        <select wire:model="divingLogArray.divingData.tank_volume_unit" class="form-input form-select-append">
                            @foreach ($this->getVolumeUnitOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <!-- Start Pressure, Start Pressure Unit, End Pressure, End Pressure Unit -->
                <div class="flex flex-col">
                    <label>Start Pressure</label>
                    <div class="form-input-group">
                        <input wire:model="divingLogArray.divingData.start_pressure" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                        <select wire:model="divingLogArray.divingData.start_pressure_unit" class="form-input form-select-append">
                            @foreach ($this->getPressureUnitOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('divingLogArray.divingData.start_pressure') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col">
                    <label>End Pressure</label>
                    <div class="form-input-group">
                        <input wire:model="divingLogArray.divingData.end_pressure" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                        <select wire:model="divingLogArray.divingData.end_pressure_unit" class="form-input form-select-append">
                            @foreach ($this->getPressureUnitOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('divingLogArray.divingData.end_pressure') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>


                <!-- Average Depth, Equipment Suit, Equipment Mask, Equipment Fins -->
                <div class="flex flex-col">
                    <label>Average Depth</label>
                    <div class="form-input-group">
                        <input wire:model="divingLogArray.divingData.average_depth" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                        <select wire:model="divingLogArray.divingData.average_depth_unit" class="form-input form-select-append">
                            @foreach ($this->getDistanceUnitOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('divingLogArray.divingData.average_depth') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>

        <div class="card mt-4">
            <!-- Start Equipment -->
            <h2 class="font-bold mb-4">Equipment</h2>

            <div class="flex flex-col gap-y-4">
                <div>
                    <label>Equipment Suit</label>
                    <input wire:model="divingLogArray.divingData.equipment_suit" type="text" class="form-input w-full" placeholder="">
                    @error('divingLogArray.divingData.equipment_suit') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Equipment Mask</label>
                    <input wire:model="divingLogArray.divingData.equipment_mask" type="text" class="form-input w-full" placeholder="">
                    @error('divingLogArray.divingData.equipment_mask') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Equipment Fins</label>
                    <input wire:model="divingLogArray.divingData.equipment_fins" type="text" class="form-input w-full" placeholder="">
                    @error('divingLogArray.divingData.equipment_fins') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <!-- Equipment BCD/Wing/Sidemount, Equipment First Stage, Equipment Second Stage, Equipment Dive Computer -->
                <div>
                    <label>Equipment BCD/Wing/Sidemount</label>
                    <input wire:model="divingLogArray.divingData.equipment_bcd_wing_sidemount" type="text" class="form-input w-full" placeholder="">
                    @error('divingLogArray.divingData.equipment_bcd_wing_sidemount') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Equipment First Stage</label>
                    <input wire:model="divingLogArray.divingData.equipment_first_stage" type="text" class="form-input w-full" placeholder="">
                    @error('divingLogArray.divingData.equipment_first_stage') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Equipment Second Stage</label>
                    <input wire:model="divingLogArray.divingData.equipment_second_stage" type="text" class="form-input w-full" placeholder="">
                    @error('divingLogArray.divingData.equipment_second_stage') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Equipment Dive Computer</label>
                    <input wire:model="divingLogArray.divingData.equipment_dive_computer" type="text" class="form-input w-full" placeholder="">
                    @error('divingLogArray.divingData.equipment_dive_computer') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <!-- Equipment Lights, Equipment Other, Equipment Weight, Equipment Weight Unit -->
                <div>
                    <label>Equipment Lights</label>
                    <input wire:model="divingLogArray.divingData.equipment_lights" type="text" class="form-input w-full" placeholder="">
                    @error('divingLogArray.divingData.equipment_lights') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col">
                    <label>Equipment Weight</label>
                    <div class="form-input-group">
                        <input wire:model="divingLogArray.divingData.equipment_weight" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                        <select wire:model="divingLogArray.divingData.equipment_weight_unit" class="form-input form-select-append">
                            @foreach ($this->getWeightUnitOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('divingLogArray.divingData.equipment_weight') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Equipment Other</label>
                    <textarea wire:model="divingLogArray.divingData.equipment_other" class="form-textarea w-full" placeholder=""></textarea>
                </div>

            </div>
            <!-- End Equipment -->
        </div>


        <div class="card mt-4">
            <div class="flex justify-end">
                <button type="button" wire:click="goToStep(4)" class="btn w-full btn-action">Next</button>
            </div>
        </div>


    </section>
@endif
