<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="mb-8 flex justify-between">
            <h1 class="page-first-title">{{ __('Create Shipping Method') }}</h1>
        </div>

        <form action="{{ route('admin.shipping.methods.store') }}" method="POST">
            @csrf
            <div class="bg-white shadow-lg rounded-sm mb-8">
                <div class="flex flex-col md:flex-row md:-mr-px">
                    <div class="grow">
                        <section class="p-6 space-y-6">
                            <!-- Information Box -->
                            <div class="flex information-box">
                                <!-- ... SVG and Information Text -->
                                <p class="text-sm"> Provide a name for the shipping method </p>
                            </div>

                            <!-- Input Fields -->
                            <div class="flex flex-wrap -mx-4 space-y-4 md:space-y-0">
                                <!-- Name -->
                                <div class="w-full px-4 md:w-1/2">
                                    <label class="block text-sm font-medium mb-1" for="name">{{ __('Method Name') }} <span class="text-rose-500">*</span></label>
                                    <input id="name" class="form-input w-full" type="text" name="name" value="{{ old('name') }}" required />
                                    @error('name')
                                        <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                    @enderror
                                </div>


                                <!-- Description -->
                                <div class="w-full px-4 md:w-1/2">
                                    <label class="block text-sm font-medium mb-1" for="description">{{ __('Description') }} <span class="text-rose-500">*</span></label>
                                    <input id="description" class="form-input w-full" type="text" name="description" value="{{ old('description') }}" required />
                                    @error('description')
                                        <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                                    @enderror
                                </div>


                            </div>
                        </section>

                        <!-- Submit Button Section -->
                        <section>
                            <div class="flex flex-col px-6 py-5 border-t border-slate-200">
                                <div class="flex self-end">
                                    <a class="btn bg-slate-500 text-white" href="{{ route('admin.shipping.methods.index') }}">{{ __('Back') }}</a>
                                    <button type="submit" class="btn bg-blue-500 hover:bg-blue-600 text-white ml-3">{{ __('Create Shipping Method') }}</button>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </form>

    </div>
</x-layout>
