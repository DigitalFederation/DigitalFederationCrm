<section class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl text-gray-800 mb-4">FreeDiving</h2>

    <div class="flex flex-col gap-y-4">

        <!-- Duration Minutes -->
        <div class="dive-log-info-wrapper">
            <div class="w-fit">
                <p class="text-admin_blue text-xl font-bold">{{$divingLog->duration_minutes}} min</p>
                <p class="text-gray-600 text-sm md:text-md">Dive Time</p>
            </div>
            <div class="w-fit">
                <!-- Stopwatch svg icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="fill-cmas_blue h-8 w-8" viewBox="0 0 16 16">
                    <path d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5V5.6z"/>
                    <path d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5zM8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3z"/>
                </svg>
            </div>
        </div>

        <!-- Dive Depth -->
        <div class="dive-log-info-wrapper">
            <div class="w-fit">
                <p class="text-admin_blue text-xl font-bold">{{$divingLog->depth}} @if(!empty($divingLog->depth))
                        {{$divingLog->depth_unit}}
                    @endif</p>
                <p class="text-gray-600 text-sm md:text-md">Depth</p>
            </div>
            <div class="w-fit">
                <!-- Arrows Down svg icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" fill="currentColor" class="stroke-cmas_blue h-8 w-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0l-3.75-3.75M17.25 21L21 17.25"/>
                </svg>

            </div>
        </div>

        <!-- Max Distance -->
        <div class="dive-log-info-wrapper">
            <div class="w-fit">
                <p class="text-admin_blue text-xl font-bold"></p>
                <p class="text-gray-600 text-sm md:text-md">Max Distance</p>
            </div>
            <div class="w-fit">
                <!-- Arrows Down svg icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" fill="currentColor" class="stroke-cmas_blue h-8 w-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0l-3.75-3.75M17.25 21L21 17.25"/>
                </svg>

            </div>
        </div>

    </div>

</section>

<section class="card mt-4" x-data="{ open: false }">

    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
        <h2 class="text-xl text-gray-800">FreeDiving Details</h2>

        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" :class="{'rotate-180': open}" class="fill-gray-600 h-8 w-8" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M3.646 4.146a.5.5 0 0 1 .708 0L8 7.793l3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 0-.708zM1 11.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5z"/>
        </svg>
    </div>

    <div class="mt-4" x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
        <table class="w-full rounded-lg">
            <tbody>
            <tr class="border-2 border-blue-100 ">
                <td class="p-2 text-gray-600  text-sm md:text-md ">Freediving Discipline</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">{{ $divingLog->freedive_discipline }}</td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md ">Static Details</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->static_details }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md ">Distance Disciplines Details</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->distance_disciplines_details }}
                </td>
            </tr>
            <!-- Equipment -->
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Suit</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->equipment_suit }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Mask</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->equipment_mask }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Fins</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->equipment_fins }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Dive Computer</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->equipment_dive_computer }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Other</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->equipment_other }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Weight</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->equipment_weight }} @if(!empty($divingLog->equipment_weight))
                        {{ $divingLog->equipment_weight_unit }}
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</section>
