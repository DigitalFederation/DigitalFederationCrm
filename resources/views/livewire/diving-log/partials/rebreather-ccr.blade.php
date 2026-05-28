@if($divingLogArray['dive_type'] == 4)
    <section id="formThirdStep">

        <div class="card">
            <h2 class="font-bold">Closed Circuit Rebreather (CCR)</h2>
            <div class="mt-4 flex flex-col gap-y-4">

                <!-- Runtime, Total Deco Time, and Depth -->
                <div>
                    <label>Total Runtime</label>
                    <input wire:model="divingLogArray.rebreatherCCRData.runtime" class="form-input numeric w-full" placeholder="0000 minutes" minlength="0" maxlength="4">
                    @error('divingLogArray.rebreatherCCRData.runtime') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label>CCR Total Deco Time</label>
                    <input wire:model="divingLogArray.rebreatherCCRData.ccr_total_deco_time" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                    @error('divingLogArray.rebreatherCCRData.ccr_total_deco_time') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>
                <div class="flex flex-col">
                    <label>Depth</label>
                    <div class="form-input-group">
                        <input wire:model="divingLogArray.rebreatherCCRData.depth" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                        <select wire:model="divingLogArray.rebreatherCCRData.depth_unit" class="form-input form-select-append">
                            @foreach ($this->getDistanceUnitOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('divingLogArray.rebreatherCCRData.depth') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <!-- Depth Unit, Bailout SAC, and Deco SAC -->
                <div>
                    <label>Bailout SAC</label>
                    <input wire:model="divingLogArray.rebreatherCCRData.bailout_sac" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                    @error('divingLogArray.rebreatherCCRData.bailout_sac') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label>Deco SAC</label>
                    <input wire:model="divingLogArray.rebreatherCCRData.deco_sac" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                    @error('divingLogArray.rebreatherCCRData.deco_sac') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <!-- Gas information -->
                <div class="mt-4">
                    <label>Gases</label>

                    @for ($i = 1; $i <= 4; $i++)
                            <?php $prefix = $i === 1 ? 'diluent' : "bailout_gas_" . $i - 1; ?>
                        <div class="mt-2 flex flex-col gap-y-4">
                            <h3 class="font-bold">{{ ucfirst(str_replace('_', ' ', $prefix)) }}</h3>
                            <div class="flex flex-col">
                                <label>Tank Volume</label>
                                <div class="form-input-group">
                                    <input wire:model="divingLogArray.rebreatherCCRData.{{ $prefix }}_tank_volume" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                                    <select wire:model="divingLogArray.rebreatherCCRData.{{ $prefix }}_tank_volume_unit" class="form-input form-select-append">
                                        @foreach ($this->getVolumeUnitOptions() as $value =>$label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('divingLogArray.rebreatherCCRData.{{ $prefix }}_tank_volume') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex flex-col">
                                <label>Start Pressure</label>
                                <div class="form-input-group">
                                    <input wire:model="divingLogArray.rebreatherCCRData.{{ $prefix }}_start_pressure" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                                    <select wire:model="divingLogArray.rebreatherCCRData.{{ $prefix }}_start_pressure_unit" class="form-input form-select-append">
                                        @foreach ($this->getPressureUnitOptions() as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('divingLogArray.rebreatherCCRData.{{ $prefix }}.start_pressure') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex flex-col">
                                <label>End Pressure</label>
                                <div class="form-input-group">
                                    <input wire:model="divingLogArray.rebreatherCCRData.{{ $prefix }}_end_pressure" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                                    <select wire:model="divingLogArray.rebreatherCCRData.{{ $prefix }}_end_pressure_unit" class="form-input form-select-append">
                                        @foreach ($this->getPressureUnitOptions() as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('divingLogArray.rebreatherCCRData.{{ $prefix }}.end_pressure') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label>Tank Type</label>
                                <select wire:model="divingLogArray.rebreatherCCRData.{{ $prefix }}_tank_type" class="form-select w-full">
                                    @foreach ($this->getTankTypeOptions() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('divingLogArray.rebreatherCCRData.{{ $prefix }}_tank_type') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label>Oxygen</label>
                                <input wire:model="divingLogArray.rebreatherCCRData.{{ $prefix }}_oxygen_percentage" class="form-input numeric w-full" placeholder="000 %" minlength="0" maxlength="3">
                                @error('divingLogArray.rebreatherCCRData.{{ $prefix }}.oxygen_percentage') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label>Helium</label>
                                <input wire:model="divingLogArray.rebreatherCCRData.{{ $prefix }}_helium_percentage" class="form-input numeric w-full" placeholder="000 %" minlength="0" maxlength="3">
                                @error('divingLogArray.rebreatherCCRData.{{ $prefix }}.helium_percentage') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <!-- Start Equipment -->
            <h2 class="font-bold mb-4">Equipment</h2>
            <div class="flex flex-col gap-y-4">
                <!-- Equipment Suit, Equipment Mask -->
                <div>
                    <label>Equipment Suit</label>
                    <input wire:model="divingLogArray.rebreatherCCRData.equipment_suit" type="text" class="form-input w-full" placeholder="Equipment Suit">
                </div>
                <div>
                    <label>Equipment Mask</label>
                    <input wire:model="divingLogArray.rebreatherCCRData.equipment_mask" type="text" class="form-input w-full" placeholder="Equipment Mask">
                </div>

                <!-- Equipment Fins, Equipment BCD Wing Sidemount, Equipment Rebreather Unit -->
                <div>
                    <label>Equipment Fins</label>
                    <input wire:model="divingLogArray.rebreatherCCRData.equipment_fins" type="text" class="form-input w-full" placeholder="Equipment Fins">
                </div>
                <div>
                    <label>Equipment BCD / Wing / Sidemount</label>
                    <input wire:model="divingLogArray.rebreatherCCRData.equipment_bcd_wing_sidemount" type="text" class="form-input w-full" placeholder="Equipment BCD / Wing / Sidemount">
                </div>
                <div>
                    <label>Equipment Rebreather Unit</label>
                    <input wire:model="divingLogArray.rebreatherCCRData.equipment_rebreather_unit" type="text" class="form-input w-full" placeholder="Equipment Rebreather Unit">
                </div>

                <!-- Equipment Dive Computer, Equipment Lights, Equipment Other -->
                <div>
                    <label>Equipment Dive Computer</label>
                    <input wire:model="divingLogArray.rebreatherCCRData.equipment_dive_computer" type="text" class="form-input w-full" placeholder="Equipment Dive Computer">
                </div>
                <div>
                    <label>Equipment Lights</label>
                    <input wire:model="divingLogArray.rebreatherCCRData.equipment_lights" type="text" class="form-input w-full" placeholder="Equipment Lights">
                </div>

                <!-- Equipment Weight, Equipment Weight Unit -->
                <div class="flex flex-col">
                    <label>Equipment Weight</label>
                    <div class="form-input-group">
                        <input wire:model="divingLogArray.rebreatherCCRData.equipment_weight" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                        <select wire:model="divingLogArray.rebreatherCCRData.equipment_weight_unit" class="form-input form-select-append">
                            @foreach ($this->getWeightUnitOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @error('divingLogArray.rebreatherCCRData.equipment_weight') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror

                <div>
                    <label>Equipment Other</label>
                    <textarea wire:model="divingLogArray.rebreatherCCRData.equipment_other" class="form-textarea w-full" placeholder="Equipment Other"></textarea>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="flex justify-end">
                <button type="button" wire:click="goToStep(4)" class="btn w-full btn-action">Next</button>
            </div>
        </div>

    </section>
@endif
