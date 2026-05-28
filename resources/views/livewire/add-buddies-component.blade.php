<!-- Modal -->
<div style="background-color: rgba(0, 0, 0, 0.8)"
    class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full" >

    <div class="p-4 max-w-xl mx-auto relative left-0 right-0 overflow-hidden mt-24">
        <div
            class="shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
            x-on:click="$dispatch('close')">
            <svg class="fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M16.192 6.344L11.949 10.586 7.707 6.344 6.293 7.758 10.535 12 6.293 16.242 7.707 17.656 11.949 13.414 16.192 17.656 17.606 16.242 13.364 12 17.606 7.758z" />
            </svg>
        </div>

        <div class="shadow w-full rounded-lg bg-white overflow-hidden block p-6">

            <h2 class="text-lg leading-6 font-medium text-gray-600 border-b border-slate-200 pb-2 mb-4">Add a Buddy</h2>

            <div class="mt-3 sm:mt-0 sm:text-left w-full">

                <div class="flex items-end w-full mb-4">
                    <div class="w-full">
                        <label for="buddies" class="block text-sm font-medium text-gray-700">{{ __('Select a buddy to add to this dive') }}</label>
                        <select
                            id="buddies"
                            name="buddies"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm rounded-r-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach($buddies as $buddy)
                                <option value="{{ $buddy->id }}">{{ $buddy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button wire:click="addBuddy(document.getElementById('buddies').value)" class="btn-primary rounded-l-none">Insert</button>
                </div>

                <div class="w-full">
                    @if(!empty($currentBuddies) && $currentBuddies->count() > 0)
                        <p class="text-gray-600 text-sm italic"> {{__('Buddies already added')}}</p>
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-200">
                                <th class="p-1">Name</th>
                                <th class="text-right"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($currentBuddies as $buddy)
                                <tr >
                                <td class="p-1">{{ $buddy->name }}</td>
                                <td class="justify-end text-right p-1">
                                    <button wire:click="removeBuddy({{ $buddy->id }})" class="btn btn-xs btn-danger">Remove</button>
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @endif
                </div>

            </div>
        </div>
    </div>

</div>
<!-- /Modal -->
