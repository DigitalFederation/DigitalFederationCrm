<div>
    {{-- Page Header --}}
    <div class="mb-6 sm:flex sm:justify-between sm:items-center">
        <div class="mb-4 sm:mb-0">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                {{ __('diving_courses.title') }}
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('diving_courses.subtitle') }}
            </p>
        </div>

        <div>
            <button type="button" wire:click="create" class="btn btn-primary">
                <x-heroicon-o-plus class="w-5 h-5 mr-1" />
                <span>{{ __('diving_courses.add_course') }}</span>
            </button>
        </div>
    </div>

    {{-- Courses Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($divingCourses as $course)
            <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                {{-- Course Image --}}
                <div class="aspect-video bg-slate-100 dark:bg-slate-700 relative">
                    @if($course->getFirstMediaUrl('course-image', 'card'))
                        <img src="{{ $course->getFirstMediaUrl('course-image', 'card') }}"
                             alt="{{ $course->display_name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <x-heroicon-o-academic-cap class="w-12 h-12 text-slate-300 dark:text-slate-500" />
                        </div>
                    @endif
                </div>

                {{-- Course Info --}}
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold text-slate-900 dark:text-white truncate">
                            {{ $course->display_name }}
                        </h3>
                        @if($course->certification_system)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 flex-shrink-0">
                                {{ $course->certification_system }}
                            </span>
                        @endif
                    </div>

                    @if($course->start_date)
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            <x-heroicon-o-calendar class="w-4 h-4 inline-block mr-1" />
                            {{ $course->start_date->format('d M Y') }}
                        </p>
                    @endif

                    @if($course->district || $course->location)
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            <x-heroicon-o-map-pin class="w-4 h-4 inline-block mr-1" />
                            @if($course->district && $course->location)
                                {{ $course->location }}, {{ $course->district->name }}
                            @elseif($course->district)
                                {{ $course->district->name }}
                            @else
                                {{ $course->location }}
                            @endif
                        </p>
                    @endif

                    @if($course->about)
                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-2 line-clamp-2">
                            {{ Str::limit(strip_tags($course->about), 100) }}
                        </p>
                    @endif

                    {{-- Actions --}}
                    <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-slate-100 dark:border-slate-700">
                        <button type="button"
                                wire:click="edit({{ $course->id }})"
                                class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors"
                                title="{{ __('common.edit') }}">
                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                        </button>
                        <button type="button"
                                wire:click="confirmDelete({{ $course->id }})"
                                class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                title="{{ __('common.delete') }}">
                            <x-heroicon-o-trash class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 mb-4">
                    <x-heroicon-o-academic-cap class="w-8 h-8 text-slate-400" />
                </div>
                <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-1">
                    {{ __('diving_courses.no_courses') }}
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                    {{ __('diving_courses.no_courses_description') }}
                </p>
                <button type="button" wire:click="create" class="btn btn-primary">
                    {{ __('diving_courses.add_first_course') }}
                </button>
            </div>
        @endforelse
    </div>

    @if($divingCourses->hasPages())
        <div class="mt-6">
            {{ $divingCourses->links() }}
        </div>
    @endif

    {{-- Create Modal --}}
    <div
        x-data
        x-show="$wire.showCreateModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title-create"
        role="dialog"
        aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Backdrop --}}
            <div
                x-show="$wire.showCreateModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-slate-900/50 dark:bg-slate-900/70"
                @click="$wire.set('showCreateModal', false)"
                aria-hidden="true"
            ></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Panel --}}
            <div
                x-show="$wire.showCreateModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 shadow-xl rounded-xl"
            >
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="modal-title-create">
                    {{ __('diving_courses.add_new_course') }}
                </h3>

                <div class="mt-4 space-y-4">
                    {{-- Course Name --}}
                    <div>
                        <label for="create_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('diving_courses.course_name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="name"
                               id="create_name"
                               placeholder="{{ __('diving_courses.course_name_placeholder') }}"
                               class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('name') ? 'border-red-300' : '' }}">
                        @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Certification System --}}
                    <div>
                        <label for="create_certification_system" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('diving_courses.certification_system') }}
                        </label>
                        <select wire:model="certification_system"
                                id="create_certification_system"
                                class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('diving_courses.select_system') }}</option>
                            @foreach(\Domain\DivingLogs\Models\DivingCourse::CERTIFICATION_SYSTEMS as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('certification_system') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- District and Location --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="create_district_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ __('diving_courses.district') }}
                            </label>
                            <select wire:model="district_id"
                                    id="create_district_id"
                                    class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('diving_courses.select_district') }}</option>
                                @foreach($districts as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('district_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="create_location" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ __('diving_courses.location') }}
                            </label>
                            <input type="text"
                                   wire:model="location"
                                   id="create_location"
                                   placeholder="{{ __('diving_courses.location_placeholder') }}"
                                   class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('location') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Start Date --}}
                    <div>
                        <label for="create_start_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('diving_courses.start_date') }}
                        </label>
                        <input type="date"
                               wire:model="start_date"
                               id="create_start_date"
                               class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('start_date') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Course Image --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('diving_courses.course_image') }}
                        </label>
                        <label for="create_courseImage"
                               class="flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 dark:border-slate-600 border-dashed rounded-lg hover:border-slate-400 dark:hover:border-slate-500 transition-colors cursor-pointer">
                            <div class="space-y-1 text-center">
                                @if($courseImage)
                                    <div class="mb-2">
                                        <img src="{{ $courseImage->temporaryUrl() }}"
                                             alt="{{ __('diving_courses.preview') }}"
                                             class="mx-auto h-24 object-cover rounded-lg">
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        {{ $courseImage->getClientOriginalName() }}
                                    </p>
                                @else
                                    <x-heroicon-o-photo class="mx-auto h-10 w-10 text-slate-400" />
                                    <div class="text-sm text-slate-600 dark:text-slate-400">
                                        <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ __('diving_courses.upload_image') }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500">PNG, JPG {{ __('diving_courses.up_to') }} 2MB</p>
                                @endif
                            </div>
                            <input id="create_courseImage"
                                   wire:model="courseImage"
                                   type="file"
                                   class="sr-only"
                                   accept="image/jpeg,image/png,image/webp">
                        </label>
                        @error('courseImage') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- About --}}
                    <div>
                        <label for="create_about" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('diving_courses.description') }}
                        </label>
                        <textarea wire:model="about"
                                  id="create_about"
                                  rows="3"
                                  placeholder="{{ __('diving_courses.description_placeholder') }}"
                                  class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('about') ? 'border-red-300' : '' }}"></textarea>
                        @error('about') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button"
                            @click="$wire.set('showCreateModal', false)"
                            class="btn btn-secondary">
                        {{ __('common.cancel') }}
                    </button>
                    <button type="button"
                            wire:click="save"
                            wire:loading.attr="disabled"
                            class="btn btn-primary">
                        <span wire:loading.remove wire:target="save">{{ __('diving_courses.save_course') }}</span>
                        <span wire:loading wire:target="save">{{ __('profile.saving') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div
        x-data
        x-show="$wire.showEditModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title-edit"
        role="dialog"
        aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Backdrop --}}
            <div
                x-show="$wire.showEditModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-slate-900/50 dark:bg-slate-900/70"
                @click="$wire.set('showEditModal', false)"
                aria-hidden="true"
            ></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Panel --}}
            <div
                x-show="$wire.showEditModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 shadow-xl rounded-xl"
            >
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="modal-title-edit">
                    {{ __('diving_courses.edit_course') }}
                </h3>

                <div class="mt-4 space-y-4">
                    {{-- Course Name --}}
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('diving_courses.course_name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="name"
                               id="edit_name"
                               placeholder="{{ __('diving_courses.course_name_placeholder') }}"
                               class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('name') ? 'border-red-300' : '' }}">
                        @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Certification System --}}
                    <div>
                        <label for="edit_certification_system" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('diving_courses.certification_system') }}
                        </label>
                        <select wire:model="certification_system"
                                id="edit_certification_system"
                                class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('diving_courses.select_system') }}</option>
                            @foreach(\Domain\DivingLogs\Models\DivingCourse::CERTIFICATION_SYSTEMS as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('certification_system') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- District and Location --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit_district_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ __('diving_courses.district') }}
                            </label>
                            <select wire:model="district_id"
                                    id="edit_district_id"
                                    class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('diving_courses.select_district') }}</option>
                                @foreach($districts as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('district_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="edit_location" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ __('diving_courses.location') }}
                            </label>
                            <input type="text"
                                   wire:model="location"
                                   id="edit_location"
                                   placeholder="{{ __('diving_courses.location_placeholder') }}"
                                   class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('location') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Start Date --}}
                    <div>
                        <label for="edit_start_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('diving_courses.start_date') }}
                        </label>
                        <input type="date"
                               wire:model="start_date"
                               id="edit_start_date"
                               class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('start_date') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Course Image --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('diving_courses.course_image') }}
                        </label>

                        {{-- Current Image Preview --}}
                        @if($editing && $editing->getFirstMediaUrl('course-image', 'thumb'))
                            <div class="flex items-start gap-4 mb-3">
                                <div class="relative">
                                    <img src="{{ $editing->getFirstMediaUrl('course-image', 'thumb') }}"
                                         alt="{{ __('diving_courses.current_image') }}"
                                         class="w-20 h-20 object-cover rounded-lg border border-slate-200 dark:border-slate-600">
                                    <button type="button"
                                            wire:click="removeImage"
                                            wire:confirm="{{ __('diving_courses.confirm_remove_image') }}"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-sm hover:bg-red-600 transition-colors">
                                        <x-heroicon-s-x-mark class="w-3 h-3" />
                                    </button>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('diving_courses.current_image') }}</p>
                            </div>
                        @endif

                        <label for="edit_courseImage"
                               class="flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 dark:border-slate-600 border-dashed rounded-lg hover:border-slate-400 dark:hover:border-slate-500 transition-colors cursor-pointer">
                            <div class="space-y-1 text-center">
                                @if($courseImage)
                                    <div class="mb-2">
                                        <img src="{{ $courseImage->temporaryUrl() }}"
                                             alt="{{ __('diving_courses.preview') }}"
                                             class="mx-auto h-24 object-cover rounded-lg">
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        {{ $courseImage->getClientOriginalName() }}
                                    </p>
                                @else
                                    <x-heroicon-o-photo class="mx-auto h-10 w-10 text-slate-400" />
                                    <div class="text-sm text-slate-600 dark:text-slate-400">
                                        <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ __('diving_courses.upload_new_image') }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500">PNG, JPG {{ __('diving_courses.up_to') }} 2MB</p>
                                @endif
                            </div>
                            <input id="edit_courseImage"
                                   wire:model="courseImage"
                                   type="file"
                                   class="sr-only"
                                   accept="image/jpeg,image/png,image/webp">
                        </label>
                        @error('courseImage') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- About --}}
                    <div>
                        <label for="edit_about" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('diving_courses.description') }}
                        </label>
                        <textarea wire:model="about"
                                  id="edit_about"
                                  rows="3"
                                  placeholder="{{ __('diving_courses.description_placeholder') }}"
                                  class="mt-1 block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('about') ? 'border-red-300' : '' }}"></textarea>
                        @error('about') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button"
                            @click="$wire.set('showEditModal', false)"
                            class="btn btn-secondary">
                        {{ __('common.cancel') }}
                    </button>
                    <button type="button"
                            wire:click="update"
                            wire:loading.attr="disabled"
                            class="btn btn-primary">
                        <span wire:loading.remove wire:target="update">{{ __('diving_courses.update_course') }}</span>
                        <span wire:loading wire:target="update">{{ __('profile.saving') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div
        x-data
        x-show="$wire.confirmingDelete"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title-delete"
        role="dialog"
        aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Backdrop --}}
            <div
                x-show="$wire.confirmingDelete"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-slate-900/50 dark:bg-slate-900/70"
                @click="$wire.set('confirmingDelete', false)"
                aria-hidden="true"
            ></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Panel --}}
            <div
                x-show="$wire.confirmingDelete"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 shadow-xl rounded-xl"
            >
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-600 dark:text-red-400" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="modal-title-delete">
                            {{ __('diving_courses.delete_course') }}
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            {{ __('diving_courses.delete_confirmation') }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button"
                            @click="$wire.set('confirmingDelete', false)"
                            class="btn btn-secondary">
                        {{ __('common.cancel') }}
                    </button>
                    <button type="button"
                            wire:click="delete"
                            wire:loading.attr="disabled"
                            class="btn bg-red-600 hover:bg-red-700 text-white">
                        {{ __('common.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
