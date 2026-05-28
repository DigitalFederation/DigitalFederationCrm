<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="mb-8 flex justify-between">
            <h1 class="page-first-title">{{ __('Create Shipping Zone') }}</h1>
        </div>

        <form action="{{ route('admin.shipping.zones.store') }}" method="POST">
            @csrf
            <div class="card">

                <div class="flex flex-col md:flex-row md:-mr-px">
                    <section class="mb-4 w-full">
                        <!-- Information Box -->
                        <x-information-box
                            title="Info" body="Create a zone by choosing a existing Sub Country Zone. Example: 'Azores' and 'Portugal'">
                        </x-information-box>

                        <!-- Input Fields -->
                        <div class="flex flex-wrap -mx-4 space-y-4 md:space-y-0">
                            <!-- Zone Name -->
                            <div class="w-full px-4 md:w-1/2">
                                <label class="block text-sm font-medium mb-1" for="name">{{ __('Zone Name') }} <span class="text-rose-500">*</span></label>
                                <input id="name" class="form-input w-full" type="text" name="name" value="{{ old('name') }}" required />
                                @error('name')
                                    <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                @enderror
                            </div>



                            <!-- Countries Selection -->
                            <div class="w-full px-4 md:w-1/2">
                                <label class="block text-sm font-medium mb-1" for="sub_zones">{{ __('Shipping Country Zones') }} <span class="text-rose-500">*</span></label>

                                <livewire:input.select-multiple
                                    wire:model="selectedSubZones"
                                    :items="$subZones"
                                    inputId="sub_zones"
                                    inputName="sub_zones[]"
                                    identifier="sub_zones"
                                    :input-selected="$selectedSubZones ?? []"
                                    :multiple="true"/>

                                @error('sub_zones')
                                    <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </section>
                </div>


                <x-forms.card-form-submit
                    :backRoute="'cmas.shipping.zones.index'"
                    buttonText="Save Shipping Zone">
                </x-forms.card-form-submit>
            </div>
        </form>

    </div>
</x-layout>
