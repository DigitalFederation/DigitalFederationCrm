<div>
    {{-- Page Header --}}
    <div class="mb-8 sm:flex sm:justify-between sm:items-center">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                {{ __('entity.public_page.title') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('entity.public_page.subtitle') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('public.entity.show', $entity) }}"
               target="_blank"
               class="btn btn-secondary flex items-center gap-2">
                <x-heroicon-o-eye class="w-5 h-5" />
                <span>{{ __('entity.public_page.view_public_page') }}</span>
            </a>
        </div>
    </div>

    {{-- Main Card Container with Tabs --}}
    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden"
         x-data="{ activeTab: @entangle('activeTab') }">

        {{-- Tab Navigation --}}
        <div class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
            <nav class="flex gap-x-1 px-4" aria-label="Tabs">
                {{-- General Tab --}}
                <button
                    @click="activeTab = 'general'"
                    :class="activeTab === 'general'
                        ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800'
                        : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:bg-slate-700/50'"
                    class="group relative py-3 px-4 border-b-2 -mb-px text-sm font-medium transition-all duration-200 rounded-t-lg flex items-center gap-2"
                >
                    <x-heroicon-o-cog-6-tooth class="w-4 h-4" />
                    <span>{{ __('entity.public_page.tabs.general') }}</span>
                </button>

                {{-- Featured Locations Tab --}}
                @role('entity-diving-services')
                <button
                    @click="activeTab = 'featured_locations'"
                    :class="activeTab === 'featured_locations'
                        ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800'
                        : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:bg-slate-700/50'"
                    class="group relative py-3 px-4 border-b-2 -mb-px text-sm font-medium transition-all duration-200 rounded-t-lg flex items-center gap-2"
                >
                    <x-heroicon-o-map-pin class="w-4 h-4" />
                    <span>{{ __('entity.public_page.tabs.featured_locations') }}</span>
                    @if(count($selectedFeaturedLocations) > 0)
                        <span
                            :class="activeTab === 'featured_locations' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-800 dark:text-indigo-300' : 'bg-slate-200 text-slate-600 dark:bg-slate-600 dark:text-slate-300'"
                            class="py-0.5 px-2 rounded-full text-xs font-semibold transition-colors"
                        >
                            {{ count($selectedFeaturedLocations) }}
                        </span>
                    @endif
                </button>
                @endrole

                {{-- Courses Tab --}}
                @role('entity-diving-services')
                <button
                    @click="activeTab = 'courses'"
                    :class="activeTab === 'courses'
                        ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800'
                        : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:bg-slate-700/50'"
                    class="group relative py-3 px-4 border-b-2 -mb-px text-sm font-medium transition-all duration-200 rounded-t-lg flex items-center gap-2"
                >
                    <x-heroicon-o-academic-cap class="w-4 h-4" />
                    <span>{{ __('entity.public_page.tabs.courses') }}</span>
                </button>
                @endrole
            </nav>
        </div>

        {{-- Tab Content --}}
        <div class="p-6">
            {{-- General Settings Tab --}}
            <div x-show="activeTab === 'general'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-cloak>

                <div class="max-w-3xl space-y-6">
                    {{-- Background Image --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('entity.public_page.background_image') }}
                        </label>

                        <div class="flex items-start gap-6">
                            {{-- Current Image Preview --}}
                            @if($currentBackgroundUrl)
                                <div class="flex-shrink-0">
                                    <div class="relative">
                                        <img src="{{ $currentBackgroundUrl }}"
                                             alt="{{ __('entity.public_page.current_background') }}"
                                             class="w-32 h-20 object-cover rounded-lg shadow-sm border border-slate-200 dark:border-slate-600">
                                        <button type="button"
                                                wire:click="removeBackgroundImage"
                                                wire:confirm="{{ __('entity.public_page.confirm_remove_background') }}"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-sm hover:bg-red-600 transition-colors">
                                            <x-heroicon-s-x-mark class="w-3 h-3" />
                                        </button>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">{{ __('entity.public_page.current_image') }}</p>
                                </div>
                            @endif

                            {{-- Upload New Image --}}
                            <div class="flex-1">
                                <label for="entityBackground"
                                       class="flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 dark:border-slate-600 border-dashed rounded-lg hover:border-slate-400 dark:hover:border-slate-500 transition-colors cursor-pointer">
                                    <div class="space-y-1 text-center">
                                        @if($entityBackground)
                                            <div class="mb-2">
                                                <img src="{{ $entityBackground->temporaryUrl() }}"
                                                     alt="{{ __('entity.public_page.preview') }}"
                                                     class="mx-auto h-20 object-cover rounded-lg">
                                            </div>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                                {{ $entityBackground->getClientOriginalName() }}
                                            </p>
                                        @else
                                            <x-heroicon-o-photo class="mx-auto h-12 w-12 text-slate-400" />
                                            <div class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                                                <span class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                                    {{ __('entity.public_page.upload_file') }}
                                                </span>
                                                <p class="pl-1">{{ __('entity.public_page.or_drag_drop') }}</p>
                                            </div>
                                            <p class="text-xs text-slate-500">
                                                {{ __('entity.public_page.image_requirements') }}
                                            </p>
                                        @endif
                                    </div>
                                    <input id="entityBackground"
                                           wire:model="entityBackground"
                                           type="file"
                                           class="sr-only"
                                           accept="image/jpeg,image/png,image/webp">
                                </label>
                                @error('entityBackground')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Public Description --}}
                    <div>
                        <label for="publicDescription" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('entity.public_page.public_description') }}
                        </label>
                        <x-forms.tinymce-editor-livewire
                            wireModel="publicDescription"
                            elementId="public-description-editor"
                        />
                        @error('publicDescription')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            {{ __('entity.public_page.description_help') }}
                        </p>
                    </div>

                    {{-- Save Button --}}
                    <div class="flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
                        <button type="button"
                                wire:click="saveGeneralSettings"
                                wire:loading.attr="disabled"
                                class="btn btn-primary">
                            <span wire:loading.remove wire:target="saveGeneralSettings">
                                {{ __('entity.public_page.save_settings') }}
                            </span>
                            <span wire:loading wire:target="saveGeneralSettings">
                                {{ __('profile.saving') }}...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Featured Locations Tab --}}
            @role('entity-diving-services')
            <div x-show="activeTab === 'featured_locations'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-cloak>

                <div class="max-w-3xl space-y-6">
                    <div>
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-1">
                                    {{ __('entity.public_page.featured_locations.title') }}
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('entity.public_page.featured_locations.description') }}
                                </p>
                            </div>
                            <a href="{{ route('entity.diving-location.create') }}"
                               class="btn btn-secondary flex items-center gap-2 flex-shrink-0">
                                <x-heroicon-o-plus class="w-4 h-4" />
                                <span>{{ __('entity.public_page.featured_locations.create_new') }}</span>
                            </a>
                        </div>

                        {{-- Select Multiple Component --}}
                        <livewire:input.select-multiple
                            :items="$availableDivingLocations"
                            :input-selected="$selectedFeaturedLocations"
                            input-name="featured_diving_locations[]"
                            input-id="featured_diving_locations"
                            identifier="featured-locations"
                            wire:key="featured-locations-select"
                        />
                    </div>

                    {{-- Preview of Selected Locations --}}
                    @if(count($selectedFeaturedLocations) > 0)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">
                                {{ __('entity.public_page.featured_locations.selected_preview') }}
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($selectedFeaturedLocations as $locationId)
                                    @if(isset($availableDivingLocations[$locationId]))
                                        <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-600">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                                <x-heroicon-o-map-pin class="w-4 h-4 text-indigo-600 dark:text-indigo-400" />
                                            </div>
                                            <span class="text-sm text-slate-700 dark:text-slate-300">
                                                {{ $availableDivingLocations[$locationId] }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-700 mb-3">
                                <x-heroicon-o-map-pin class="w-6 h-6 text-slate-400" />
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                {{ __('entity.public_page.featured_locations.no_locations_selected') }}
                            </p>
                        </div>
                    @endif

                    {{-- Save Button --}}
                    <div class="flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
                        <button type="button"
                                wire:click="saveFeaturedLocations"
                                wire:loading.attr="disabled"
                                class="btn btn-primary">
                            <span wire:loading.remove wire:target="saveFeaturedLocations">
                                {{ __('entity.public_page.featured_locations.save_locations') }}
                            </span>
                            <span wire:loading wire:target="saveFeaturedLocations">
                                {{ __('profile.saving') }}...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            @endrole

            {{-- Courses Tab --}}
            @role('entity-diving-services')
            <div x-show="activeTab === 'courses'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-cloak>

                {{-- Embed the existing ManageDivingCourses component --}}
                <livewire:entity.diving-course.manage-diving-courses wire:key="diving-courses-management" />
            </div>
            @endrole
        </div>
    </div>
</div>
