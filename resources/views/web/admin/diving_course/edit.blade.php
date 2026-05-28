<x-layout>
    <div class="previous-layout-classes">
        <!-- Page header -->
        <div class="mb-8 flex justify-between">
            <h1 class="page-first-title">{{ __('Edit Diving Course') }}</h1>
        </div>

        <form action="{{ route('admin.diving-course.update', $divingCourse->id) }}" method="POST" class="card p-6 max-w-2xl mx-auto">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="entity_id" class="block text-sm font-medium mb-1">{{ __('Entity') }}</label>
                    <select name="entity_id" id="entity_id" class="form-input w-full">
                        <option value="">{{ __('Select Entity') }}</option>
                        @foreach($entities as $entity)
                            <option value="{{ $entity->id }}" @if(old('entity_id', $divingCourse->entity_id) == $entity->id) selected @endif>{{ $entity->name }}</option>
                        @endforeach
                    </select>
                    @error('entity_id')<div class="text-xs mt-1 text-rose-500">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="certification_id" class="block text-sm font-medium mb-1">{{ __('Certification') }}</label>
                    <select name="certification_id" id="certification_id" class="form-input w-full">
                        <option value="">{{ __('Select Certification') }}</option>
                        @foreach($certifications as $certification)
                            <option value="{{ $certification->id }}" @if(old('certification_id', $divingCourse->certification_id) == $certification->id) selected @endif>{{ $certification->name }}</option>
                        @endforeach
                    </select>
                    @error('certification_id')<div class="text-xs mt-1 text-rose-500">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium mb-1">{{ __('Start Date') }}</label>
                    <input type="date" name="start_date" id="start_date" class="form-input w-full" value="{{ old('start_date', $divingCourse->start_date ? $divingCourse->start_date->format('Y-m-d') : '') }}">
                    @error('start_date')<div class="text-xs mt-1 text-rose-500">{{ $message }}</div>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="about" class="block text-sm font-medium mb-1">{{ __('About / Description') }}</label>
                    <textarea name="about" id="about" rows="5" class="form-input w-full">{{ old('about', $divingCourse->about) }}</textarea>
                    @error('about')<div class="text-xs mt-1 text-rose-500">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="flex gap-4 justify-end mt-8">
                <a href="{{ route('admin.diving-course.index') }}" class="btn bg-slate-500 text-white">{{ __('Back') }}</a>
                <button type="submit" class="btn btn-action">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</x-layout>
