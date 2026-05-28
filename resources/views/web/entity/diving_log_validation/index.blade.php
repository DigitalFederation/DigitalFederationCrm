<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-4">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="page-first-title">
                    {{ __('Dives Pending Validation') }}
                </h1>
            </div>
            <div>
                <a href="{{ route('entity.diving-log-validation.approved') }}" class="btn btn-primary">
                    {{ __('View Approved Dives') }}
                </a>
            </div>
        </div>
        <x-information-box title="Information"
                           body="As an entity representative, you can validate pending dive logs submitted by any diver using this platform. Your validation confirms the dive details, contributing to the reliability of diver logbooks. Review the pending dives below and use the 'Accept' button to validate them." />

        <div class="sm:flex sm:justify-center sm:items-center mb-5">
            @if(!empty($pendingDivingLogs) && $pendingDivingLogs->count() > 0)
                <!-- Desktop View -->
                <div class="bg-white shadow-lg rounded-sm border border-slate-200 mb-8 w-full hidden md:block">
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <!-- Table header -->
                            <thead
                                class="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-t border-b border-slate-200">
                            <tr>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">{{ __('Diver') }}</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">{{ __('Dive Type') }}</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">{{ __('Date') }}</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">{{ __('Status') }}</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-right">{{ __('Actions') }}</div>
                                </th>
                            </tr>
                            </thead>
                            <!-- Table body -->
                            <tbody class="text-sm divide-y divide-slate-200">
                            @foreach($pendingDivingLogs as $divingLog)
                                <tr>
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                        {{ $divingLog->individual->name }} {{ $divingLog->individual->surname }}
                                    </td>
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                        {{ @$divingLog->dive_type->name }}
                                    </td>
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                        {{ Carbon\Carbon::parse($divingLog->date_and_time)->translatedFormat('d/m/Y @ H:i') }}
                                    </td>
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                        <x-tables.badge :status="ucwords($divingLog->stateName())"
                                                        :color="$divingLog->colorState()" />
                                    </td>
                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px items-end">
                                        <div class="gap-x-2 flex justify-end items-end">
                                            <x-dynamic-table-buttons type="show"
                                                                     :route="route('entity.diving-log-validation.show', $divingLog->id)" />
                                            @if($divingLog->status_class == \Domain\DivingLogs\States\PendingDivingLogState::class)
                                                <x-dynamic-table-buttons type="accept"
                                                                         :route="route('entity.diving-log-validation.update', $divingLog->id)"
                                                                         method="PUT" />
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
                <div class="md:hidden">
                    @php
                        $logsByYear = $pendingDivingLogs->groupBy(function($item) {
                            try {
                                return Carbon\Carbon::parse($item->date_and_time)->format('Y');
                            } catch (\Exception $e) {
                                // Log the error or handle it as appropriate for your application
                                return 'Unknown Year';
                            }
                        });
                    @endphp
                    @foreach($logsByYear as $year => $logsInYear)
                        <div class="text-xl font-bold mb-2 mt-4">{{ $year }}</div>
                        <div class="flex flex-col gap-y-4">
                            @foreach($logsInYear as $divingLog)
                                <a href="{{ route('entity.diving-log-validation.show', $divingLog->id) }}"
                                   class="w-full relative">
                                    <div class="border-gray-200 rounded-md bg-white relative">
                                        <div class="px-4 py-2 flex items-center justify-between sm:px-6">
                                            <div
                                                class="absolute -top-2 -left-2 {{ $divingLog->colorState() }} rounded-full p-1">
                                                {!! $divingLog->svgState() !!}
                                            </div>

                                            <!-- Date & Time -->
                                            <div
                                                class="flex flex-col items-center justify-center border-r-2 border-gray-200 pr-4 w-16 ">
                                                <div class="text-xs font-bold uppercase">
                                                    {{ date('M', strtotime($divingLog->date_and_time)) }}
                                                </div>
                                                <div class="text-xl font-semibold">
                                                    {{ date('d', strtotime($divingLog->date_and_time)) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ date('h:i', strtotime($divingLog->date_and_time)) }}
                                                </div>
                                            </div>

                                            <!-- Dive Info -->
                                            <div class="flex flex-col ml-4">
                                                <div class="text-md text-gray-700 truncate">
                                                    {{ $divingLog->individual->name }} {{ $divingLog->individual->surname }}
                                                </div>
                                                <div class="text-xs text-gray-400 truncate">
                                                    {{ @$divingLog->dive_type->name }}
                                                </div>

                                                @if($divingLog->location)
                                                    <div class="text-sm text-cyan-700 flex items-center mt-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24" stroke-width="1.5"
                                                             stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                                        </svg>
                                                        <p class="truncate">{{ $divingLog->location->name }}</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Chevron Icon -->
                                            <div class="ml-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                     viewBox="0 0 20 20" fill="currentColor">
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

            @else
                <x-utility.no-data></x-utility.no-data>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{$pendingDivingLogs->links()}}
        </div>
    </div>
</x-layout>
