@if($divingLogArray['dive_type'] == 2)
    <section id="formThirdStep">

        <div class="card">
            <h2 class="font-bold">Freediving Data</h2>
            <div class="mt-4 flex flex-col gap-y-4">
                <div>
                    <label>Freedive Discipline</label>
                    <select wire:model="divingLogArray.freedivingData.freedive_discipline" wire:change="updateFreediveDiscipline" class="form-select w-full">
                        @foreach ($this->getFreedivingDisciplineOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('divingLogArray.freedivingData.freedive_discipline') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>
                @if($divingLogArray['freedivingData']['freedive_discipline'] == 'Static')
                    <div>
                        <label>Warm Ups</label>
                        <input wire:model="divingLogArray.freedivingData.warm_ups" class="numeric form-input w-full" placeholder="0000" minlength="0" maxlength="4">
                    </div>
                    <div>
                        <label>Max Time</label>
                        <input wire:model="divingLogArray.freedivingData.max_time" class="numeric form-input w-full" placeholder="0000 minutes" minlength="0" maxlength="4">
                    </div>
                    <div>
                        <label>Contraction Time</label>
                        <input wire:model="divingLogArray.freedivingData.contraction_time" class="numeric form-input w-full" placeholder="0000 minutes" minlength="0" maxlength="4">
                    </div>
                @else
                    <div>
                        <label>Time</label>
                        <input wire:model="divingLogArray.freedivingData.time" class="numeric form-input w-full" placeholder="0000 minutes" minlength="0" maxlength="4">
                    </div>
                @endif
                @if(in_array($divingLogArray['freedivingData']['freedive_discipline'], ['Free Immersion', 'Constant Weight', 'Constant No Fins', 'Variable Weight']))
                    <div class="flex flex-col">
                        <label>Max Depth</label>
                        <div class="form-input-group">
                            <input wire:model="divingLogArray.freedivingData.max_depth" class="numeric form-input w-full border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                            <select wire:model="divingLogArray.freedivingData.max_depth_unit" class="form-input form-select-append">
                                @foreach ($this->getDistanceUnitOptions() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col">
                            <label>Max Distance</label>
                            <div class="form-input-group">
                                <input wire:model="divingLogArray.freedivingData.max_distance" class="numeric form-input w-full border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                                <select wire:model="divingLogArray.freedivingData.max_distance_unit" class="form-input form-select-append">
                                    @foreach ($this->getDistanceUnitOptions() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
            </div>

            <div class="card mt-4">
                <!-- Start Equipment -->
                <h2 class="font-bold mb-4">Equipment</h2>
                <div class="flex flex-col gap-y-4">
                    <div>
                        <label>Equipment Suit</label>
                        <input wire:model="divingLogArray.freedivingData.equipment_suit" type="text" class="form-input w-full" placeholder="">
                    </div>
                    <div>
                        <label>Equipment Mask</label>
                        <input wire:model="divingLogArray.freedivingData.equipment_mask" type="text" class="form-input w-full" placeholder="">
                    </div>
                    <div>
                        <label>Equipment Fins</label>
                        <input wire:model="divingLogArray.freedivingData.equipment_fins" type="text" class="form-input w-full" placeholder="">
                    </div>
                    <div>
                        <label>Equipment Dive Computer</label>
                        <input wire:model="divingLogArray.freedivingData.equipment_dive_computer" type="text" class="form-input w-full" placeholder="">
                    </div>
                    <div class="flex flex-col">
                        <label>Equipment Weight</label>
                        <div class="form-input-group">
                            <input wire:model="divingLogArray.freedivingData.equipment_weight" class="numeric form-input w-full border-none bg-transparent outline-none" placeholder="0000" minlength="0" maxlength="4">
                            <select wire:model="divingLogArray.freedivingData.equipment_weight_unit" class="form-input form-select-append">
                                @foreach ($this->getWeightUnitOptions() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label>Equipment Other</label>
                        <textarea wire:model="divingLogArray.freedivingData.equipment_other" class="form-textarea w-full" placeholder=""></textarea>
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
