@if($divingLogArray['dive_type'] == 5)
<section id="formThirdStep">
    <div class="card">
        <h2 class="font-bold">Short Circuit Rebreather (SCR)</h2>

        <div class="flex flex-col gap-y-4">

            <!-- Runtime, SCR Total Deco Time, Depth -->
            <div>
            <label>Runtime</label>
            <input wire:model="divingLogArray.rebreatherSCRData.runtime" class="form-input numeric w-full" placeholder="0000 minutes" minlength="0" maxlength="4">
            @error('divingLogArray.rebreatherSCRData.runtime') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>
            <div>
            <label>SCR Total Deco Time</label>
            <input wire:model="divingLogArray.rebreatherSCRData.scr_total_deco_time" class="form-input numeric w-full" placeholder="0000 minutes" minlength="0" maxlength="4">
            @error('divingLogArray.rebreatherSCRData.scr_total_deco_time') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col">
            <label>Depth</label>
            <div class="form-input-group">
                <input wire:model="divingLogArray.rebreatherSCRData.depth" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                <select wire:model="divingLogArray.rebreatherSCRData.depth_unit" class="form-input form-select-append">
                @foreach ($this->getDistanceUnitOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
                </select>
            </div>
            @error('divingLogArray.rebreatherSCRData.depth') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <!-- Depth Unit, Weight, Weight Unit -->
            <div class="flex flex-col">
            <label>Weight</label>
            <div class="form-input-group">
                <input wire:model="divingLogArray.rebreatherSCRData.weight" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                <select wire:model="divingLogArray.rebreatherSCRData.weight_unit" class="form-input form-select-append">
                @foreach ($this->getWeightUnitOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
                </select>
            </div>
            @error('divingLogArray.rebreatherSCRData.weight') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <!-- Bailout SAC, Deco SAC, Tank Volume -->
            <div>
            <label>Bailout SAC</label>
            <input wire:model="divingLogArray.rebreatherSCRData.bailout_sac" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
            @error('divingLogArray.rebreatherSCRData.bailout_sac') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>
            <div>
            <label>Deco SAC</label>
            <input wire:model="divingLogArray.rebreatherSCRData.deco_sac" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
            @error('divingLogArray.rebreatherSCRData.deco_sac') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
            <label>Tank Volume</label>
            <div class="form-input-group">
                <input wire:model="divingLogArray.rebreatherSCRData.tank_volume" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                <select wire:model="divingLogArray.rebreatherSCRData.tank_volume_unit" class="form-input form-select-append">
                @foreach ($this->getVolumeUnitOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
                </select>
            </div>
            @error('divingLogArray.rebreatherSCRData.tank_volume') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <!-- Tank Volume Unit, Oxygen Percentage, Setpoint -->
            <div>
            <label>Oxygen</label>
            <input wire:model="divingLogArray.rebreatherSCRData.oxygen_percentage" class="form-input numeric w-full" placeholder="000 %" minlength="0" maxlength="3">
            @error('divingLogArray.rebreatherSCRData.oxygen_percentage') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>
            <div>
            <label>Setpoint</label>
            <input wire:model="divingLogArray.rebreatherSCRData.setpoint" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
            @error('divingLogArray.rebreatherSCRData.setpoint') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>


            <!-- Start Pressure, Start Pressure Unit, End Pressure -->
            <div class="flex flex-col">
            <label>Start Pressure</label>
            <div class="form-input-group">
                <input wire:model="divingLogArray.rebreatherSCRData.start_pressure" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                <select wire:model="divingLogArray.rebreatherSCRData.start_pressure_unit" class="form-input form-select-append">
                @foreach ($this->getPressureUnitOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
                </select>
            </div>
            @error('divingLogArray.rebreatherSCRData.start_pressure') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
            <label>End Pressure</label>
            <div class="form-input-group">
                <input wire:model="divingLogArray.rebreatherSCRData.end_pressure" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                <select wire:model="divingLogArray.rebreatherSCRData.end_pressure_unit" class="form-input form-select-append">
                @foreach ($this->getPressureUnitOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
                </select>
            </div>
            @error('divingLogArray.rebreatherSCRData.end_pressure') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
            <label>Deco Gas Tank Volume</label>
            <div class="form-input-group">
                <input wire:model="divingLogArray.rebreatherSCRData.deco_tank_volume" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                <select wire:model="divingLogArray.rebreatherSCRData.deco_tank_volume_unit" class="form-input form-select-append">
                @foreach ($this->getVolumeUnitOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
                </select>
            </div>
            @error('divingLogArray.rebreatherSCRData.deco_tank_volume') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <!-- Deco Oxygen Percentage, Deco Setpoint, Deco Start Pressure -->

            <div>
            <label>Deco Gas Oxygen</label>
            <input wire:model="divingLogArray.rebreatherSCRData.deco_oxygen_percentage" class="form-input numeric w-full" placeholder="000 %" minlength="0" maxlength="3">
            @error('divingLogArray.rebreatherSCRData.deco_oxygen_percentage') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>
            <div>
            <label>Deco Gas Setpoint</label>
            <input wire:model="divingLogArray.rebreatherSCRData.deco_setpoint" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
            @error('divingLogArray.rebreatherSCRData.deco_setpoint') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
            <label>Deco Gas Start Pressure</label>
            <div class="form-input-group">
                <input wire:model="divingLogArray.rebreatherSCRData.deco_start_pressure" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                <select wire:model="divingLogArray.rebreatherSCRData.deco_start_pressure_unit" class="form-input form-select-append">
                @foreach ($this->getPressureUnitOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
                </select>
            </div>
            @error('divingLogArray.rebreatherSCRData.deco_start_pressure') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>
            <!-- Deco Start Pressure Unit, Deco End Pressure, Deco End Pressure Unit -->
            <div class="flex flex-col">
            <label>Deco Gas End Pressure</label>
            <div class="form-input-group">
                <input wire:model="divingLogArray.rebreatherSCRData.deco_end_pressure" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                <select wire:model="divingLogArray.rebreatherSCRData.deco_end_pressure_unit" class="form-input form-select-append">
                @foreach ($this->getPressureUnitOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
                </select>
            </div>
            @error('divingLogArray.rebreatherSCRData.deco_end_pressure') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
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
            <input wire:model="divingLogArray.rebreatherSCRData.equipment_suit" type="text" class="form-input w-full" placeholder="">
            </div>
            <div>
            <label>Equipment Mask</label>
            <input wire:model="divingLogArray.rebreatherSCRData.equipment_mask" type="text" class="form-input w-full" placeholder="">
            </div>

            <!-- Equipment Fins, Equipment BCD Wing Sidemount, Equipment Rebreather Unit -->
            <div>
            <label>Equipment Fins</label>
            <input wire:model="divingLogArray.rebreatherSCRData.equipment_fins" type="text" class="form-input w-full" placeholder="">
            </div>
            <div>
            <label>Equipment BCD / Wing / Sidemount</label>
            <input wire:model="divingLogArray.rebreatherSCRData.equipment_bcd_wing_sidemount" type="text" class="form-input w-full" placeholder="">
            </div>
            <div>
            <label>Equipment Rebreather Unit</label>
            <input wire:model="divingLogArray.rebreatherSCRData.equipment_rebreather_unit" type="text" class="form-input w-full" placeholder="">
            </div>

            <!-- Equipment Dive Computer, Equipment Lights, Equipment Other -->
            <div>
            <label>Equipment Dive Computer</label>
            <input wire:model="divingLogArray.rebreatherSCRData.equipment_dive_computer" type="text" class="form-input w-full" placeholder="">
            </div>
            <div>
            <label>Equipment Lights</label>
            <input wire:model="divingLogArray.rebreatherSCRData.equipment_lights" type="text" class="form-input w-full" placeholder="">
            </div>

            <!-- Equipment Weight, Equipment Weight Unit -->
            <div class="flex flex-col">
            <label>Equipment Weight</label>
            <div class="form-input-group">
                <input wire:model="divingLogArray.rebreatherSCRData.equipment_weight" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                <select wire:model="divingLogArray.rebreatherSCRData.equipment_weight_unit" class="form-input form-select-append">
                @foreach ($this->getWeightUnitOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
                </select>
            </div>
            @error('divingLogArray.rebreatherSCRData.equipment_weight') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
            <label>Equipment Other</label>
            <textarea wire:model="divingLogArray.rebreatherSCRData.equipment_other" class="form-textarea w-full" placeholder=""></textarea>
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
