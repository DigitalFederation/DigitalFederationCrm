<x-layout>
    <div class="previous-layout-classes">
        <!-- Page header -->
        <div class="mb-8 flex justify-between">
            <h1 class="text-2xl font-semibold">{{ __('Create Shipping Destination') }}</h1>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.shipping.sub-zones.store') }}" method="POST">
            @csrf
            <div class="card">

                <x-information-box
                    title="Info" body="Create a sub zone by choosing a name and a country. Example: 'Azores' and 'Portugal'">
                </x-information-box>

                <div class="flex flex-col md:flex-row">
                    <!-- Sub-Zone Name -->
                    <div class="w-full p-6">
                        <label class="block text-sm font-medium mb-1" for="name">{{ __('Destination Name') }} <span class="text-rose-500">*</span></label>
                        <input id="name" class="form-input w-full" type="text" name="name" required />
                    </div>

                    <!-- Countries Selection -->
                    <div class="w-full p-6">
                        <label class="block text-sm font-medium mb-1" for="country">{{ __('Country') }}</label>
                        <select id="country" class="form-select w-full" name="country_id" required>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"> {{ $country->name }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Actions -->
                <x-forms.card-form-submit
                    :backRoute="'cmas.shipping.sub-zones.index'"
                    buttonText="Save Sub-Zone">
                </x-forms.card-form-submit>

            </div>
        </form>
    </div>
</x-layout>
