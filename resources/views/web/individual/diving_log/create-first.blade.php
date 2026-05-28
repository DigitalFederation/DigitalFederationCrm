<x-layout>
    <div class="px-2 py-4 sm:px-6 lg:px-8 w-full">

      <!-- Page header -->
      <div class="sm:flex sm:justify-between sm:items-center mb-5 lg:w-full">

        <!-- Left: Title section with improved styling -->
        <div class="w-full bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-6 shadow-lg">
            <div class="max-w-4xl">
            <!-- Header content wrapper -->
            <div class="flex items-center gap-x-4 mb-4">
                <div class="bg-white/10 rounded-lg p-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6 md:w-7 md:h-7 text-white" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                    <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                    <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                </svg>
                </div>
                <div>
                <h1 class="text-2xl text-white font-bold tracking-tight">{{ __('New dive log') }}</h1>
                <p class="text-blue-100 text-sm md:text-base">Record #{{ $nextDiveNumber ?? '001' }}</p>
                </div>
            </div>

            <!-- Enhanced description -->
            <div class="flex items-start gap-x-3 px-1">
                <p class="text-sm md:text-base text-blue-50 leading-relaxed">
                Create a dive log by filling out the form below. Start with the essential details like type, date, category,
                and location — you can add more specific information in the following steps.
                </p>
            </div>
            </div>
        </div>

      </div>

      <div class="sm:w-2/3 lg:w-full">
        <livewire:diving-log-form :individual="Auth()->user()->individuals()->first()" isFirstDive="true"></livewire:diving-log-form>
      </div>

    </div>
  </x-layout>
