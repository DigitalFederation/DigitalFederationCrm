<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-4">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="page-first-title">
                    {{  __('Diving Logs') }}
                </h1>
            </div>

        </div>


        <x-information-box title="{{ __('diving_log.information') }}"
                           body="{{ __('diving_log.privilege_description') }}" />

        <div class="sm:flex sm:justify-center sm:items-center mb-5">

            <x-dynamic-table
                :headers="[__('diving_log.dive_type'), __('diving_log.number'), __('main.Date'), __('main.status'), ['text'=>__('diving_log.diver'), 'alignment'=>'text-right'], __('main.Actions')]"
                :items="$divingLogs">
                @foreach($divingLogs as $diving)
                    <tr>
                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                            {{ @$diving->dive_type->name }}
                            <div
                                class="sm:hidden text-xs">{{ date('d/m/Y @ h:m', strtotime($diving->date_and_time)) }}</div>
                        </td>

                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                            {{ @$diving->sequence?->log_number }}
                        </td>

                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($diving->date_and_time)->translatedFormat('d/m/Y @ H:i') }}
                        </td>

                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                            <x-tables.badge :status="ucfirst($diving->stateName())" :color="$diving->colorState()" />
                        </td>

                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-right">
                            {{ $diving->individual->full_name }}
                        </td>

                        <td class="px-2 first:pl-5 last:pr-5 py-3 flex justify-end space-x-4">

                            @if($diving->status_class == \Domain\DivingLogs\States\PendingDivingLogState::class)

                                <x-dynamic-table-buttons type="accept"
                                                         :route="route('federation.diving-log-validation.update', $diving->id)"
                                                         method="PUT" />
                            @endif

                            <a href="{{ route(Request::segment(1).'.diving-log.show', $diving->id) }}"
                               class="text-slate-400 hover:text-slate-500 rounded-full" title="Show">
                                <span class="sr-only">{{ __('Show') }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 27 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>

                        </td>
                    </tr>
                @endforeach
            </x-dynamic-table>


            <!-- Mobile View -->
            <div class="sm:hidden">
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
