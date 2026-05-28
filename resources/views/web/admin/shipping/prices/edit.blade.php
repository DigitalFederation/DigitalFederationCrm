<x-layout>
    <div class="previous-layout-classes">
        <!-- Page header -->
        <div class="mb-8 flex justify-between">
            <h1 class="page-first-title">{{ __('Edit Shipping Price') }}</h1>
        </div>

        <form action="{{ route('admin.shipping.prices.update', $price) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="flex flex-col md:flex-row md:-mr-px">
                    <div class="grow">
                        <section class="pb-4 space-y-6">
                            <!-- Information Box -->
                            <x-information-box
                                title="Info" body="Set the price for shipping based on the selected zone, weight range, and shipping method">
                            </x-information-box>

                            <!-- Input Fields -->
                            <div class="flex flex-wrap -mx-4 space-y-4 md:space-y-0 gap-y-4">
                                <!-- Zone Selection -->
                                <div class="w-full px-4 md:w-1/2">
                                    <label class="block text-sm font-medium mb-1" for="zone_id">{{ __('Shipping Zone') }} <span class="text-rose-500">*</span></label>
                                    <select name="zone_id" id="zone_id" class="form-select w-full" required>
                                        @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}" {{ $price->zone_id == $zone->id ? 'selected' : '' }}>
                                                {{ $zone->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('zone_id')
                                    <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Weight Range Selection -->
                                <div class="w-full px-4 md:w-1/2">
                                    <label class="block text-sm font-medium mb-1" for="weight_id">{{ __('Weight Range') }} <span class="text-rose-500">*</span></label>
                                    <select name="weight_id" id="weight_id" class="form-select w-full" required>
                                        @foreach($weights as $weight)
                                            <option value="{{ $weight->id }}" {{ $price->weight_id == $weight->id ? 'selected' : '' }}>{{ $weight->range }}</option>
                                        @endforeach
                                    </select>
                                    @error('weight_id')
                                    <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Method Selection -->
                                <div class="w-full px-4 md:w-1/2">
                                    <label class="block text-sm font-medium mb-1" for="method_id">{{ __('Shipping Method') }} <span class="text-rose-500">*</span></label>
                                    <select name="method_id" id="method_id" class="form-select w-full" required>
                                        @foreach($methods as $method)
                                            <option value="{{ $method->id }}" {{ $price->method_id == $method->id ? 'selected' : '' }}>{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('method_id')
                                    <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Price Input -->
                                <div class="w-full px-4 md:w-1/2">
                                    <label class="block text-sm font-medium mb-1" for="price">{{ __('Price') }} <span class="text-rose-500">*</span></label>
                                    <input id="price" class="form-input w-full" type="text" name="price" value="{{ old('price', $price->price) }}" required />
                                    @error('price')
                                    <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        <!-- Actions -->
                        <x-forms.card-form-submit
                            :backRoute="'cmas.shipping.prices.index'"
                            buttonText="Update Shipping Price">
                        </x-forms.card-form-submit>

                    </div>
                </div>
            </div>
        </form>

    </div>
</x-layout>
