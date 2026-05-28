<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0 flex justify-items-center items-center gap-x-4">

                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-32 h-32 md:w-16 md:h-16" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                    <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                    <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                </svg>

                <div class="gap-y-4">
                    <h1 class="text-2xl md:text-3xl text-slate-700 font-bold">{{ __('Edit dive log') }}</h1>
                    <p class="text-sm">Edit a dive log record by filling the following form. Complete the details of your dive</p>
                </div>

            </div>

        </div>
        <livewire:diving-log-form :individual="Auth()->user()->individuals()->first()" :oldDivingLog="$divingLog" :isEditMode="true"/>


    </div>
</x-layout>
