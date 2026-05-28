<x-layout>

    <div class="p-4 ">


        <section class="flex flex-col">
            @if($divingLog->validation->count() == 0)
                <p> This diving log has <strong> not been validated yet</strong>.</p>
                <p><strong>Diver:</strong> {{  $divingLog->individual->full_name }}</p>
            @endif
        </section>


        <div class="my-4 flex flex-col md:flex-row justify-between gap-4 FIRST">

            <!-- Dive General Information -->
            <section class=" bg-white shadow-md rounded-lg p-6 w-full md:w-1/2">
                <h2 class="text-xl text-gray-800 mb-4">Dive</h2>

                <div class="flex flex-col gap-y-4">

                    <!-- Dive Number -->
                    <div class="dive-log-info-wrapper">
                        <div class="w-fit">
                            <p class="text-admin_blue text-xl font-bold">{{$divingLog->sequence?->log_number}}</p>
                            <p class="text-gray-600 text-sm md:text-md">Dive Number</p>
                        </div>
                        <div class="w-fit">
                            <!-- Flag svg icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="text-admin_blue"
                                 class="fill-cmas_blue h-8 w-8" viewBox="0 0 16 16">
                                <path
                                    d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21.294 21.294 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21.317 21.317 0 0 0 14 7.655V1.222z" />
                            </svg>
                        </div>
                    </div>
                    <!-- Dive Date -->
                    <div class="dive-log-info-wrapper">
                        <div class="w-fit">
                            <p class="text-admin_blue text-xl font-bold">{{ \Carbon\Carbon::parse($divingLog->date_and_time)->translatedFormat('d/m/Y H:i') }}</p>
                            <p class="text-gray-600 text-sm md:text-md">Dive Date</p>
                        </div>
                        <div class="w-fit">
                            <!-- Calendar svg icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="fill-cmas_blue h-8 w-8" viewBox="0 0 16 16">
                                <path
                                    d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                <path
                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                            </svg>
                        </div>
                    </div>
                    <!-- Dive Buddy -->
                    <div class="dive-log-info-wrapper" x-data="{ openBuddy: false }" x-cloak>
                        <div class="w-fit">
                            @if($divingLog->buddies)
                                @foreach($divingLog->buddies as $buddy)
                                    <p class="text-admin_blue @if(count($divingLog->buddies) > 1) text-lg @else text-xl @endif font-bold">{{ $buddy->name }}</p>
                                @endforeach
                            @endif
                            <p class="text-gray-600 text-sm md:text-md">Dive Buddy</p>
                        </div>

                    </div>

                </div>

            </section>

            <!-- Dive Environment -->
            <section class=" bg-white shadow-md rounded-lg p-6 w-full md:w-1/2">
                <h2 class="text-xl text-gray-800 mb-4">Environment</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-admin_blue text-xl font-bold">{{ $divingLog->environment_entry }}</p>
                        <p class="text-gray-600 text-sm md:text-md">Entry</p>
                    </div>
                    <div>
                        <p class="text-admin_blue text-xl font-bold">{{ $divingLog->environment_water_type }}</p>
                        <p class="text-gray-600 text-sm md:text-md">Water Type</p>
                    </div>
                    <div>
                        <p class="text-admin_blue text-xl font-bold">{{ $divingLog->environment_current }}</p>
                        <p class="text-gray-600 text-sm md:text-md">Current</p>
                    </div>
                    <div>
                        <p class="text-admin_blue text-xl font-bold">{{ $divingLog->environment_surface }}</p>
                        <p class="text-gray-600 text-sm md:text-md">Surface</p>
                    </div>
                </div>
            </section>


            <!-- Dive Location -->
            @if(!empty($divingLog->location))
                <section class="w-full md:w-1/2">
                    <x-diving_log.info-block-location :location="$divingLog->location" />
                </section>
            @endif

        </div>

        <div class="my-4 flex flex-col md:flex-row justify-between gap-4">

            <!-- Water and Air temperature and Water Visibility -->
            <section class=" bg-white shadow-md rounded-lg p-6 w-full md:w-1/2">
                <h2 class="text-xl text-gray-800 mb-4">Water & Air, Temperature and Visibility</h2>

                <div class="flex flex-col gap-y-4">
                    <!-- Dive Water Temperature -->
                    <div class="dive-log-info-wrapper">
                        <div class="w-fit">
                            <p class="text-admin_blue text-2xl font-bold">{{ $divingLog->environment_water_temperature }} {{ $divingLog->environment_water_temperature_unit }}</p>
                            <p class="text-gray-600 text-sm md:text-md">Water Temperature</p>
                        </div>
                        <div class="w-fit">
                            <!-- Temperature svg icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="fill-cmas_blue h-8 w-8" viewBox="0 0 16 16">
                                <path d="M8 14a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                <path
                                    d="M8 0a2.5 2.5 0 0 0-2.5 2.5v7.55a3.5 3.5 0 1 0 5 0V2.5A2.5 2.5 0 0 0 8 0zM6.5 2.5a1.5 1.5 0 1 1 3 0v7.987l.167.15a2.5 2.5 0 1 1-3.333 0l.166-.15V2.5z" />
                            </svg>
                        </div>
                    </div>
                    <!-- Dive Air Temperature -->
                    <div class="dive-log-info-wrapper">
                        <div class="w-fit">
                            <p class="text-admin_blue text-2xl font-bold">{{ $divingLog->environment_air_temperature }} {{ $divingLog->environment_air_temperature_unit }}</p>
                            <p class="text-gray-600 text-sm md:text-md">Air Temperature</p>
                        </div>
                        <div class="w-fit">
                            <!-- Temperature svg icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="fill-cmas_blue h-8 w-8" viewBox="0 0 16 16">
                                <path
                                    d="M5 12.5a1.5 1.5 0 1 1-2-1.415V2.5a.5.5 0 0 1 1 0v8.585A1.5 1.5 0 0 1 5 12.5z" />
                                <path
                                    d="M1 2.5a2.5 2.5 0 0 1 5 0v7.55a3.5 3.5 0 1 1-5 0V2.5zM3.5 1A1.5 1.5 0 0 0 2 2.5v7.987l-.167.15a2.5 2.5 0 1 0 3.333 0L5 10.486V2.5A1.5 1.5 0 0 0 3.5 1zm5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0v-1a.5.5 0 0 1 .5-.5zm4.243 1.757a.5.5 0 0 1 0 .707l-.707.708a.5.5 0 1 1-.708-.708l.708-.707a.5.5 0 0 1 .707 0zM8 5.5a.5.5 0 0 1 .5-.5 3 3 0 1 1 0 6 .5.5 0 0 1 0-1 2 2 0 0 0 0-4 .5.5 0 0 1-.5-.5zM12.5 8a.5.5 0 0 1 .5-.5h1a.5.5 0 1 1 0 1h-1a.5.5 0 0 1-.5-.5zm-1.172 2.828a.5.5 0 0 1 .708 0l.707.708a.5.5 0 0 1-.707.707l-.708-.707a.5.5 0 0 1 0-.708zM8.5 12a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0v-1a.5.5 0 0 1 .5-.5z" />
                            </svg>
                        </div>
                    </div>
                    <!-- Dive Water Visibility -->
                    <div class="dive-log-info-wrapper">
                        <div class="w-fit">
                            <p class="text-admin_blue text-2xl font-bold">{{ $divingLog->environment_water_visibility }} {{ $divingLog->environment_water_visibility_unit }}</p>
                            <p class="text-gray-600 text-sm md:text-md">Water Visibility</p>
                        </div>
                        <div class="w-fit">
                            <!-- Temperature svg icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="fill-cmas_blue h-8 w-8" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                      d="M7.21.8C7.69.295 8 0 8 0c.109.363.234.708.371 1.038.812 1.946 2.073 3.35 3.197 4.6C12.878 7.096 14 8.345 14 10a6 6 0 0 1-12 0C2 6.668 5.58 2.517 7.21.8zm.413 1.021A31.25 31.25 0 0 0 5.794 3.99c-.726.95-1.436 2.008-1.96 3.07C3.304 8.133 3 9.138 3 10a5 5 0 0 0 10 0c0-1.201-.796-2.157-2.181-3.7l-.03-.032C9.75 5.11 8.5 3.72 7.623 1.82z" />
                                <path fill-rule="evenodd"
                                      d="M4.553 7.776c.82-1.641 1.717-2.753 2.093-3.13l.708.708c-.29.29-1.128 1.311-1.907 2.87l-.894-.448z" />
                            </svg>
                        </div>
                    </div>
                </div>

            </section>

            <!-- DEPENDS ON DIVE TYPE -->
            @if(!empty($divingLog->dive_details['type']) && !empty($divingLog->dive_details['details']))
                <section class="w-full md:w-1/2">
                    @switch($divingLog->dive_details['type'])
                        @case(\App\Enums\DivingLogDiveTypeEnum::Diving)
                            <x-diving_log.info-block-diving :divingLog="$divingLog->dive_details['details']" />
                            @break
                        @case(\App\Enums\DivingLogDiveTypeEnum::ExtendedRange)
                            <x-diving_log.info-block-extended-range :divingLog="$divingLog->dive_details['details']" />
                            @break
                        @case(\App\Enums\DivingLogDiveTypeEnum::Freediving)
                            <x-diving_log.info-block-free-diving :divingLog="$divingLog->dive_details['details']" />
                            @break
                        @case(\App\Enums\DivingLogDiveTypeEnum::RebreatherScr)
                            <x-diving_log.info-block-rebreather-scr :divingLog="$divingLog->dive_details['details']" />
                            @break
                        @case(\App\Enums\DivingLogDiveTypeEnum::RebreatherCcr)
                            <x-diving_log.info-block-rebreather-ccr :divingLog="$divingLog->dive_details['details']" />
                            @break
                    @endswitch
                </section>
            @endif

            <!-- Wildlife -->
            @if($divingLog->wildlife)
                <section class=" bg-white shadow-md rounded-lg p-6 w-full md:w-1/2">
                    <h2 class="text-xl text-gray-800 mb-4">Wildlife</h2>
                    <div class="flex items-center">
                        <p class="text-gray-600">{{ $divingLog->wildlife }}</p>
                    </div>
                </section>
            @endif

        </div>
        <div class="my-4 flex flex-col md:flex-row justify-between gap-4">
            <!-- Comments -->
            @if($divingLog->notes)
                <section class=" bg-white shadow-md rounded-lg p-6 w-full">
                    <h2 class="text-xl text-gray-800 mb-4">Comments</h2>
                    <p class="text-gray-600">{{ $divingLog->notes }}</p>
                </section>
            @endif

        </div>


        @if($divingLog->status_class == \Domain\DivingLogs\States\PendingDivingLogState::class)
            <section>
                <form action="{{ route('federation.diving-log-validation.update', $divingLog->id) }}" method="POST"
                      onsubmit="return confirm('{{ __('Are you sure you want to approve this diving.') }}')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn-primary text-xl w-full">{{ __('Approve Dive') }}</button>
                </form>
            </section>
        @endif


    </div>


</x-layout>
