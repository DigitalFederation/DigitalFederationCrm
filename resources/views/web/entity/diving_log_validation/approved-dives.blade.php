<x-layout>
    <div class="previous-layout-classes">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-4">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="page-first-title">
                    {{ __('Dives You Approved') }}
                </h1>
            </div>
        </div>

        <x-information-box title="Information"
            body="This page displays all the dives that have been specifically approved by your entity. You can review your past approvals and access detailed information about each dive." />

        <div class="sm:flex sm:justify-center sm:items-center mb-5">
            @if (!empty($validationsByThisEntity) && $validationsByThisEntity->count() > 0)
                <!-- Desktop View -->
                <div class="bg-white shadow-lg rounded-sm border border-slate-200 mb-8 w-full hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
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
                                        <div class="font-semibold text-left">{{ __('Dive Date') }}</div>
                                    </th>
                                    <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div class="font-semibold text-left">{{ __('Approved Date') }}</div>
                                    </th>
                                    <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                        <div class="font-semibold text-right">{{ __('Actions') }}</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-200">
                                @foreach ($validationsByThisEntity as $validation)
                                    <tr>
                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                            {{ $validation->divingLog?->individual?->name }}
                                            {{ $validation->divingLog?->individual?->surname }}
                                        </td>
                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                            {{ @$validation->divingLog?->dive_type?->name }}
                                        </td>
                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                            {{ Carbon\Carbon::parse($validation->divingLog?->date_and_time)->translatedFormat('d/m/Y @ H:i') }}
                                        </td>
                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                            {{ Carbon\Carbon::parse($validation->validated_at)->translatedFormat('d/m/Y @ H:i') }}
                                        </td>
                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px items-end">
                                            <div class="gap-x-2 flex justify-end items-end">
                                                <x-dynamic-table-buttons type="show" :route="route(
                                                    'entity.diving-log-validation.show',
                                                    $validation->divingLog?->id,
                                                )" />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile View (Simplified - No Grouping by Year) -->
                <div class="md:hidden flex flex-col gap-y-4">
                    @foreach ($validationsByThisEntity as $validation)
                        <a href="{{ route('entity.diving-log-validation.show', $validation->divingLog?->id) }}"
                            class="w-full relative">
                            <div class="border-gray-200 rounded-md bg-white relative">
                                <div class="px-4 py-2 flex items-center justify-between sm:px-6">
                                    <div class="absolute -top-2 -left-2 bg-green-500 rounded-full p-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>

                                    <!-- Date & Time of Dive -->
                                    <div
                                        class="flex flex-col items-center justify-center border-r-2 border-gray-200 pr-4 w-16 ">
                                        @php
                                            $dateTime = $validation->divingLog? Carbon\Carbon::parse($validation->divingLog->date_and_time) : null;
                                        @endphp
                                        @if($dateTime)
                                        <div class="text-xs font-bold uppercase">
                                            {{ $dateTime->format('M') }}
                                        </div>
                                        <div class="text-xl font-semibold">
                                            {{ $dateTime->format('d') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $dateTime->format('H:i') }}
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Dive Info -->
                                    <div class="flex-1 flex flex-col ml-4 overflow-hidden">
                                        <div class="text-md text-gray-700 truncate">
                                            {{ $validation->divingLog?->individual?->name }}
                                            {{ $validation->divingLog?->individual?->surname }}
                                        </div>
                                        <div class="text-xs text-gray-400 truncate">
                                            {{ @$validation->divingLog?->dive_type?->name }}
                                        </div>
                                        <div class="text-xs text-green-600">
                                            Approved: {{ Carbon\Carbon::parse($validation->validated_at)->format('d/m/Y') }}
                                        </div>

                                        @if ($validation->divingLog?->location)
                                            <div class="text-sm text-cyan-700 flex items-center mt-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-5 h-5 flex-shrink-0">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                                </svg>
                                                <p class="truncate ml-1">
                                                    {{ $validation->divingLog->location->name }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Chevron Icon -->
                                    <div class="ml-auto pl-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <x-utility.no-data></x-utility.no-data>
            @endif
        </div>

        <div class="mt-8">
            {{ $validationsByThisEntity->links() }}
        </div>
    </div>
</x-layout>
