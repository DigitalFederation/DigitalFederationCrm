<x-layout>
    <div class="previous-layout-classes">
        <!-- Page header -->
        <div class="mb-8 flex justify-between">
            <h1 class="page-first-title">{{ __('Edit Shipping Weight') }}</h1>
        </div>

        <form action="{{ route('admin.shipping.weights.update', $weight) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white shadow-lg rounded-sm mb-8">
                <div class="flex flex-col md:flex-row md:-mr-px">
                    <div class="grow">
                        <section class="p-6 space-y-6">
                            <!-- Information Box -->
                            <!-- ... SVG and Information Text -->
                            <div class="flex information-box">
                                <!-- ... SVG and Information Text -->
                                <p class="text-sm"> Specify the weight range for the shipping method. </p>
                            </div>


                            <!-- Input Fields -->
                            <div class="flex flex-wrap -mx-4 space-y-4 md:space-y-0">
                                <!-- Shipping Method Selection -->
                                <div class="w-full px-4 md:w-1/4">
                                    <label class="block text-sm font-medium mb-1"
                                           for="method_id">{{ __('Shipping Method') }} <span
                                            class="text-rose-500">*</span></label>
                                    <select id="method_id" name="method_id" class="form-select w-full" required>
                                        <!-- Assuming you pass a collection of shipping methods to the view -->
                                        @foreach ($shippingMethods as $method)
                                            <option
                                                value="{{ $method->id }}" {{ old('method_id', $weight->method_id) == $method->id ? 'selected' : '' }}>{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('method_id')
                                    <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Minimum Range -->
                                <div class="w-full px-4 md:w-1/4">
                                    <label class="block text-sm font-medium mb-1"
                                           for="range">{{ __('Range/ClassGroup') }}</label>
                                    <input id="range" class="form-input w-full" type="text" name="range"
                                           value="{{ old('range', $weight->range) }}" required />
                                    @error('range')
                                    <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Minimum Weight -->
                                <div class="w-full px-4 md:w-1/4">
                                    <label class="block text-sm font-medium mb-1"
                                           for="minimum_weight">{{ __('Minimum Weight') }} <span
                                            class="text-rose-500">*</span></label>
                                    <input id="minimum_weight" class="form-input w-full" type="text"
                                           name="minimum_weight"
                                           value="{{ old('minimum_weight', $weight->minimum_weight) }}" required />
                                    @error('minimum_weight')
                                    <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Maximum Weight -->
                                <div class="w-full px-4 md:w-1/4">
                                    <label class="block text-sm font-medium mb-1"
                                           for="maximum_weight">{{ __('Maximum Weight') }} <span
                                            class="text-rose-500">*</span></label>
                                    <input id="maximum_weight" class="form-input w-full" type="text"
                                           name="maximum_weight"
                                           value="{{ old('maximum_weight', $weight->maximum_weight) }}" required />
                                    @error('maximum_weight')
                                    <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </section>
                        <!-- Submit Button Section -->
                        <section>
                            <div class="flex flex-col px-6 py-5 border-t border-slate-200">
                                <div class="flex self-end">
                                    <a class="btn bg-slate-500 text-white"
                                       href="{{ route('admin.shipping.weights.index') }}">{{ __('Back') }}</a>
                                    <button type="submit"
                                            class="btn bg-blue-500 hover:bg-blue-600 text-white ml-3">{{ __('Update Shipping Weight') }}</button>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout>
