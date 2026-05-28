@props(['divingLocations', 'group', 'districts' => collect(), 'searchName' => '', 'districtId' => ''])

<div class="previous-layout-classes">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-4">

        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="page-first-title">
                {{ __('diving_location.diving_spots') }}
            </h1>
        </div>

        <!-- Right: Actions -->
        <div class="grid sm:grid-cols-2 gap-2">
            <!-- Link to Global Map -->
            <a href="{{ route('public.diving-locations') }}" target="_blank"
               class="btn bg-blue-500 hover:bg-blue-600 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-2" width="20"
                     height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                    <path d="M9 4v13" />
                    <path d="M15 7v5.5" />
                    <path
                        d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" />
                    <path d="M19 18v.01" />
                </svg>
                <span class="ml-2">{{ __('diving_location.view_global_map') }}</span>
            </a>

            <!-- Create Diving Spot Button -->
            <a href="{{ route(Request::segment(1).'.diving-location.create') }}" class="btn btn-action">
                <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                    <path class="fill-current"
                          d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm6 13h-5v5h-2v-5H6v-2h5V6h2v5h5v2z" />
                </svg>
                <span class="ml-2">{{ __('diving_location.create_diving_spot') }}</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg border border-slate-200 mb-4 p-4">
        <form method="GET" action="{{ url()->current() }}" class="flex flex-col sm:flex-row gap-4">
            <!-- Name Filter -->
            <div class="flex-1">
                <label for="search_name" class="block text-sm font-medium text-slate-700 mb-1">
                    {{ __('diving_location.name') }}
                </label>
                <input type="text"
                       id="search_name"
                       name="search_name"
                       value="{{ $searchName }}"
                       placeholder="{{ __('diving_location.search_by_name') }}"
                       class="form-input w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- District Filter -->
            <div class="flex-1">
                <label for="district_id" class="block text-sm font-medium text-slate-700 mb-1">
                    {{ __('diving_location.district') }}
                </label>
                <select id="district_id"
                        name="district_id"
                        class="form-select w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">{{ __('diving_location.all_districts') }}</option>
                    @foreach($districts as $id => $name)
                        <option value="{{ $id }}" {{ (string) $districtId === (string) $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="btn bg-blue-500 hover:bg-blue-600 text-white">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {{ __('diving_location.filter') }}
                </button>
                @if($searchName || $districtId)
                    <a href="{{ url()->current() }}" class="btn bg-slate-100 hover:bg-slate-200 text-slate-700">
                        {{ __('diving_location.clear') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="sm:flex sm:justify-center sm:items-center mb-5">

        @if(!empty($divingLocations) && $divingLocations->count() > 0)

            <!-- Table -->
            <div class="bg-white shadow-lg rounded-sm border border-slate-200 mb-8 w-full">
                <!-- Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="table-auto w-full">
                        <!-- Table header -->
                        <thead
                            class="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-t border-b border-slate-200">
                        <tr>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('diving_location.name') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('diving_location.native_name') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('diving_location.district') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('diving_location.region') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('diving_location.depth') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('diving_location.dive_type') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('diving_location.owner') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-right"></div>
                            </th>
                        </tr>
                        </thead>
                        <!-- Table body -->
                        <tbody class="text-sm divide-y divide-slate-200">
                        <!-- Row -->
                        @foreach($divingLocations as $diving)

                            <tr>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    {{ $diving->name }}
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    {{ $diving->native_name }}
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    {{ $diving->district?->name ?? '-' }}
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    {{ $diving->region }}
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    {{ $diving->depth ?? '-' }}
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    @if(is_array($diving->dive_type) && count($diving->dive_type) > 0)
                                        {{ collect($diving->dive_type)->map(fn($type) => __('diving_location.' . strtolower(str_replace(' ', '_', $type))))->implode(', ') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    <div class="text-left">{{ $diving->owner?->name }}
                                        ({{class_basename($diving->owner_type)}})
                                    </div>
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px justify-end items-center">

                                    <div class="flex justify-end items-center gap-x-2">

                                        <x-dynamic-table-buttons type="edit"
                                                                 :route="route($group . '.diving-location.edit', $diving->id)" />

                                        @if(empty($diving->divingLog))
                                            <x-dynamic-table-buttons type="delete"
                                                                     :route="route($group . '.diving-location.delete', $diving->id)"
                                                                     method="DELETE" />
                                        @endif
                             
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Mobile View -->
            <div class="sm:hidden flex flex-col gap-y-4">
                @foreach($divingLocations as $diving)
                    <div class="flex flex-col gap-y-4">
                        @php
                            $userType = \Domain\Users\Actions\GetUserTypeAction::execute(auth()->user());
                            $canDelete = false;

                            // Case 1: User owns this location
                            if (null != $diving->owner && (string)$diving->owner->id === (string)$userType->id) {
                                $canDelete = true;
                            }
                            // Case 2: Federation user and location belongs to an affiliated entity
                            elseif ($group == 'federation' && class_exists($diving->owner_type) && is_a($diving->owner_type, \Domain\Entities\Models\Entity::class, true)) {
                                // Get the entity
                                $entity = \Domain\Entities\Models\Entity::find($diving->owner_id);

                                if ($entity) {
                                    // Check if entity belongs to this federation
                                    $entityBelongsToFederation = $entity->federations()
                                        ->where('federation_id', $userType->id)
                                        ->exists();

                                    $canDelete = $entityBelongsToFederation;
                                }
                            }
                        @endphp

                        <div class="border-gray-200 rounded-md bg-white relative">
                            <div class="px-4 py-2 flex items-center justify-between sm:px-6">
                                <!-- Dive Info -->
                                <div class="flex flex-col flex-1">
                                    <div class="text-md text-gray-700 truncate">
                                        {{ $diving->name }}
                                    </div>
                                    <div class="text-xs text-gray-400 truncate">
                                        {{ $diving->district?->name ?? $diving->region }}
                                    </div>
                                </div>

                                <div class="ml-auto flex gap-2">
                                    @if($canDelete)
                                        <a href="{{ route($group . '.diving-location.edit', $diving->id) }}"
                                           class="text-blue-500">
                                            <svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                                                <path
                                                    d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z" />
                                            </svg>
                                        </a>

                                        @if(empty($diving->divingLog))
                                            <form action="{{ route($group . '.diving-location.delete', $diving->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this dive spot?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current"
                                                         viewBox="0 0 24 24">
                                                        <path
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            fill="none" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route($group . '.diving-location.show', $diving->id) }}"
                                           class="text-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current"
                                                 viewBox="0 0 24 24">
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            <!-- No documents uploaded -->
            <div class="sm:flex flex-col text-center mx-auto">

                <p class="my-6 text-center text-gray-700 text-xl font-bold ">
                    {{ __('diving_location.no_locations_available') }}
                </p>

                <a href="{{ route(Request::segment(1) . '.diving-location.create') }}"
                   class="font-bold underline">{{ __('diving_location.create_one_now') }}</a>
            </div>

        @endif
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{$divingLocations->links()}}
    </div>
</div>
