<x-layout>

    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="page-first-title">
                    {{ __('diving_buddy.my_buddies') }}
                </h1>
            </div>

        </div>


          <!-- Form to add new Buddy -->
          <div class="bg-white shadow-lg rounded-sm p-4 mb-4">
              <h2 class="font-semibold text-xl mb-3 text-gray-600">{{ __('diving_buddy.edit_buddy') }}</h2>
              <div class="information-box flex mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 md:h-6 md:w-6 mr-4" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <circle cx="12" cy="12" r="9"></circle>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    <polyline points="11 12 12 12 12 16 13 16"></polyline>
                </svg>
                <p class="text-xs md:text-sm">{{ __('diving_buddy.add_buddy_instructions') }}</p>
              </div>

              <form action="{{ route('individual.diving-buddy.update', $buddy) }}" method="POST" class="flex flex-col md:flex-row md:items-end gap-2 md:gap-4">
                  @csrf
                  @method('PUT')
                  <div class="mb-3">
                      <label class="block text-sm font-semibold mb-2" for="name">{{ __('diving_buddy.name') }}</label>
                      <input class="form-input w-full" id="name" name="name" type="text" value="{{ $buddy->name }}" required>
                  </div>
                  <div class="mb-3">
                      <label class="block text-sm font-semibold mb-2" for="cmas_code">{{ __('main.CMAS Code') }}</label>
                      <input class="form-input w-full" id="cmas_code" name="cmas_code" value="{{ $buddy->cmas_code }}" type="text">
                  </div>
                  <div class="mb-3">
                      <button type="submit" class="btn btn-action w-full md:w-auto">{{ __('diving_buddy.save_buddy') }}</button>
                  </div>
              </form>
          </div>


    </div>

</x-layout>
