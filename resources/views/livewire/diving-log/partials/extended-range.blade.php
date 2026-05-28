@if($divingLogArray['dive_type'] == 3)


    <section id="formThirdStep">
        <div class="card">
            <h2 class="font-bold">Extended Range</h2>
            <div class="mt-4 flex flex-col gap-y-4">

                <div>
                    <label>Total Runtime</label>
                    <input wire:model="divingLogArray.extendedRangeData.total_runtime" class="numeric form-input w-full" placeholder="0000 minutes" minlength="0" maxlength="4">
                    @error('divingLogArray.extendedRangeData.total_runtime') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Total Deco Time</label>
                    <input wire:model="divingLogArray.extendedRangeData.total_deco_time" class="numeric form-input w-full" placeholder="0000 minutes" minlength="0" maxlength="4">
                    @error('divingLogArray.extendedRangeData.total_deco_time') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col">
                    <label>Depth</label>
                    <div class="form-input-group">
                        <input wire:model="divingLogArray.extendedRangeData.depth" class="form-input numeric border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                        <select wire:model="divingLogArray.extendedRangeData.depth_unit" class="form-input form-select-append">
                            @foreach ($this->getDistanceUnitOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('divingLogArray.extendedRangeData.depth') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Configuration</label>
                    <input wire:model="divingLogArray.extendedRangeData.configuration" type="text" class="form-input w-full" placeholder="">
                </div>

                <div>
                    <label>Bottom SAC</label>
                    <input wire:model="divingLogArray.extendedRangeData.sac_bottom_sac" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                    @error('divingLogArray.extendedRangeData.sac_bottom_sac') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>SAC</label>
                    <input wire:model="divingLogArray.extendedRangeData.sac_sac" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                    @error('divingLogArray.extendedRangeData.sac_sac') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>Deco SAC</label>
                    <input wire:model="divingLogArray.extendedRangeData.sac_deco_sac" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                    @error('divingLogArray.extendedRangeData.sac_deco_sac') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>S.I. Before</label>
                    <input wire:model="divingLogArray.extendedRangeData.details_si_before" type="text" class="form-input w-full" placeholder="">
                    @error('divingLogArray.extendedRangeData.details_si_before') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>GF Set</label>
                    <input wire:model="divingLogArray.extendedRangeData.details_gf_set" type="text" class="form-input w-full" placeholder="">
                </div>

                <div>
                    <label>Gradient Factor END</label>
                    <input wire:model="divingLogArray.extendedRangeData.details_gradient_factor_end" type="text" class="form-input w-full" placeholder="">
                </div>

                <div>
                    <label>CNS Start</label>
                    <input wire:model="divingLogArray.extendedRangeData.details_cns_start" type="text" class="form-input w-full" placeholder="">
                </div>

                <div>
                    <label>CNS End</label>
                    <input wire:model="divingLogArray.extendedRangeData.details_cns_end" type="text" class="form-input w-full" placeholder="">
                </div>

                <div>
                    <label>OTU Start</label>
                    <input wire:model="divingLogArray.extendedRangeData.details_otu_start" type="text" class="form-input w-full" placeholder="">
                </div>

                <div>
                    <label>OTU End</label>
                    <input wire:model="divingLogArray.extendedRangeData.details_otu_end" type="text" class="form-input w-full" placeholder="">
                </div>

                @for ($i = 1; $i <= 4; $i++)
                        <?php $prefix = $i === 1 ? 'back_gas' : "deco_gas_" . $i-1; ?>
                    <div class="mt-2 flex flex-col gap-y-4">
                        <h3 class="font-bold">{{ ucfirst(str_replace('_', ' ', $prefix)) }}</h3>
                        <div class="flex flex-col">
                            <label>Tank Volume</label>
                            <div class="form-input-group">
                                <input wire:model="divingLogArray.extendedRangeData.{{ $prefix }}_tank_volume" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                                <select wire:model="divingLogArray.extendedRangeData.{{ $prefix }}_tank_volume_unit" class="form-input form-select-append">
                                    @foreach ($this->getVolumeUnitOptions() as $value =>$label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('divingLogArray.extendedRangeData.deco_gas_{{ $prefix }}_tank_volume') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex flex-col">
                            <label>Start Pressure</label>
                            <div class="form-input-group">
                                <input wire:model="divingLogArray.extendedRangeData.{{ $prefix }}_start_pressure" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                                <select wire:model="divingLogArray.extendedRangeData.{{ $prefix }}_start_pressure_unit" class="form-input form-select-append">
                                    @foreach ($this->getPressureUnitOptions() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('divingLogArray.extendedRangeData.deco_gases.{{ $prefix }}.start_pressure') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex flex-col">
                            <label>End Pressure</label>
                            <div class="form-input-group">
                                <input wire:model="divingLogArray.extendedRangeData.{{ $prefix }}_end_pressure" class="form-input numeric w-full" placeholder="0000" minlength="0" maxlength="4">
                                <select wire:model="divingLogArray.extendedRangeData.{{ $prefix }}_end_pressure_unit" class="form-input form-select-append">
                                    @foreach ($this->getPressureUnitOptions() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('divingLogArray.extendedRangeData.deco_gases.{{ $prefix }}.end_pressure') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label>Tank Type</label>
                            <select wire:model="divingLogArray.extendedRangeData.{{ $prefix }}_tank_type" class="form-select w-full">
                                @foreach ($this->getTankTypeOptions() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('divingLogArray.extendedRangeData.{{ $prefix }}.tank_type') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label>Oxygen</label>
                            <input wire:model="divingLogArray.extendedRangeData.{{ $prefix }}_oxygen_percentage" class="form-input numeric w-full" placeholder="000 %" minlength="0" maxlength="3">
                            @error('divingLogArray.extendedRangeData.deco_gases.{{ $prefix }}.oxygen_percentage') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label>Helium</label>
                            <input wire:model="divingLogArray.extendedRangeData.{{ $prefix }}_helium_percentage" class="form-input numeric w-full" placeholder="000 %" minlength="0" maxlength="3">
                            @error('divingLogArray.extendedRangeData.deco_gases.{{ $prefix }}.helium_percentage') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endfor

            </div>
        </div>

        <div class="card mt-4">
            <div class="flex justify-end">
                <button type="button" wire:click="goToStep(4)" class="btn w-full btn-action">Next</button>
            </div>
        </div>

    </section>
@endif
