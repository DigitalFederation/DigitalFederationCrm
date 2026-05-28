<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-4">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="page-first-title">
                    {{ __('diving_log.my_dives') }}
                </h1>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center">
                <a href="{{ route('individual.diving-log.create') }}" class="btn btn-primary">
                    {{ __('diving_log.add_dive') }}
                </a>
            </div>
        </div>


        <x-information-box
            :title="__('diving_log.submission_and_validation')"
            :body="__('diving_log.submission_instructions')"></x-information-box>

        <!-- FILTER RESULTS -->
        <x-filter-form
            :post="route('individual.diving-log.index')">
            <x-forms.filter-input-select :label="__('diving_log.filters.dive_type')" name="filter_type" :options="$diveTypes" />
            <x-forms.filter-input-select :label="__('diving_log.filters.dive_category')" name="filter_category" :options="$diveCategories" />
        </x-filter-form>

        <div class="sm:flex sm:justify-center sm:items-center mb-5">
            {{-- Desktop/Tablet Table --}}
            <div class="hidden sm:block w-full">
                <x-dynamic-table
                    :headers="[__('diving_log.table.date'), __('diving_log.table.dive_type'), __('diving_log.table.dive_category'), __('diving_log.table.number'), __('diving_log.table.status'), __('diving_log.table.actions')]">

                    @foreach($divingLogs as $diving)
                        <tr>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <x-tables.date-card :date="$diving->date_and_time" />
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                {{ @$diving->dive_type->name }}
                                <div
                                    class="sm:hidden text-xs">{{ date('d/m/Y @ h:m', strtotime($diving->date_and_time)) }}</div>
                            </td>

                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                {{ $diving->category }}
                            </td>

                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                {{ $diving->sequence?->log_number }}
                            </td>


                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                <x-tables.badge :status="ucfirst($diving->stateName())" :color="$diving->colorState()" />
                            </td>

                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap  items-end">
                                <div class="flex justify-end items-center gap-x-2">

                                    <div
                                        x-data="{ open: false }"
                                        x-on:close-modal.window="open=false">

                                        <button
                                            type="button"
                                            x-on:click="open=true"
                                            class="btn-xs btn-info">
                                            {{ __('diving_log.buddies') }}
                                        </button>

                                        <!-- Livewire Component and Modal -->
                                        <div
                                            x-show="open"
                                            x-on:close.window="open = false"
                                            class="fixed z-10 inset-0 overflow-y-auto"
                                            aria-labelledby="modal-title"
                                            role="dialog"
                                            aria-modal="true"
                                            x-cloak>
                                            @livewire('add-buddies-component', ['divingLogId' => $diving->id])
                                        </div>

                                    </div>

                                    <x-dynamic-table-buttons type="show"
                                                             :route="route(Request::segment(1).'.diving-log.show', $diving->id)" />
                                    <x-dynamic-table-buttons type="edit"
                                                             :route="route(Request::segment(1).'.diving-log.edit', $diving->id)" />
                                    <x-dynamic-table-buttons type="delete"
                                                             :route="route(Request::segment(1).'.diving-log.delete', $diving->id)"
                                                             method="DELETE" />

                                </div>
                            </td>
                        </tr>
                    @endforeach

                </x-dynamic-table>
            </div>

            <!-- Mobile View -->
            <div class="sm:hidden w-full">
                @foreach($divingLogsByYear as $year => $divingLogsInYear)
                    <div class="text-xl font-bold mb-2 mt-4">{{ $year }}</div>
                    <div class="flex flex-col gap-y-4">
                        @foreach($divingLogsInYear as $diving)
                            <a href="{{ route('individual.diving-log.show', $diving->id) }}" class="w-full relative">
                                <div class="border-gray-200 rounded-md bg-white relative">
                                    <div class="px-4 py-2 flex items-center justify-between sm:px-6">

                                        <div
                                            class="absolute -top-2 -left-2 {{ $diving->colorState() }} rounded-full p-1">
                                            {!! $diving->svgState() !!}
                                        </div>

                                        <!-- Date & Time -->
                                        <div
                                            class="flex flex-col items-center justify-center border-r-2 border-gray-200 pr-4 w-16 ">
                                            <div class="text-xs font-bold uppercase">
                                                {{ date('M', strtotime($diving->date_and_time)) }}
                                            </div>
                                            <div class="text-xl font-semibold">
                                                {{ date('d', strtotime($diving->date_and_time)) }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ date('h:i', strtotime($diving->date_and_time)) }}
                                            </div>
                                        </div>

                                        <!-- Dive Info -->
                                        <div class="flex flex-col ml-4">

                                            <div class="text-md text-gray-700 truncate">
                                                {{ $diving->category }}
                                            </div>
                                            <div class="text-xs text-gray-400 truncate">
                                                {{ @$diving->dive_type->name }}
                                            </div>

                                            @if($diving->location)
                                                <div class="text-sm text-cyan-700 flex items-center mt-2">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                         class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                                    </svg>

                                                    <p class="truncate">{{ $diving->location->name }}</p>
                                                </div>
                                            @endif

                                        </div>

                                        <!-- Chevron Icon -->
                                        <div class="ml-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                 fill="currentColor">
                                                <path fill-rule="evenodd"
                                                      d="M5.707 5.293a1 1 0 0 0-1.414 1.414L9.586 12 4.293 17.293a1 1 0 1 0 1.414 1.414l6-6a1 1 0 0 0 0-1.414l-6-6z"
                                                      clip-rule="evenodd" />
                                            </svg>
                                        </div>

                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>


        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{$divingLogs->links()}}
        </div>
    </div>


</x-layout>
