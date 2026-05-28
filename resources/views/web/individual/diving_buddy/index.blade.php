<x-layout>

    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center">
            <!-- Left: Title -->
            <div class="mb-4">
                <h1 class="page-first-title">
                    {{ __('diving_buddy.my_buddies') }}
                </h1>
            </div>

        </div>


        <!-- Form to add new Buddy -->
        <x-information-box
            :title="__('diving_buddy.add_new_buddy')"
            :body="__('diving_buddy.add_buddy_instructions')"></x-information-box>

        <div class="card mb-4">

            <form action="{{ route('individual.diving-buddy.store') }}" method="POST"
                  class="flex flex-col md:flex-row md:items-end gap-2 md:gap-4">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-semibold mb-2" for="name">{{ __('diving_buddy.name') }}</label>
                    <input class="form-input w-full" id="name" name="name" type="text" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-semibold mb-2" for="cmas_code">{{ __('main.CMAS Code') }}</label>
                    <input class="form-input w-full" id="cmas_code" name="cmas_code" type="text">
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-action w-full md:w-auto">{{ __('diving_buddy.add_buddy') }}</button>
                </div>
            </form>
        </div>

        @if(!empty($buddies) && $buddies->count() > 0)

            <div class="bg-white shadow-lg rounded-sm border border-slate-200 mb-8 w-full">
                <div class="overflow-x-auto">
                    <x-dynamic-table
                        :headers="[__('diving_buddy.table.name'), __('main.CMAS Code'), __('diving_buddy.table.actions')]">
                        @foreach($buddies as $buddy)
                            <tr>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-1/2">{{ $buddy->name }}</td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-1/3">{{ $buddy->cmas_code }}</td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap flex justify-end">
                                    <div class="flex justify-end items-center gap-x-2">
                                        <x-dynamic-table-buttons type="edit"
                                                                 :route="route('individual.diving-buddy.edit', $buddy->id)" />
                                        <x-dynamic-table-buttons type="delete"
                                                                 :route="route('individual.diving-buddy.destroy', $buddy->id)"
                                                                 method="DELETE" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-dynamic-table>

                </div>
            </div>
        @else
            <x-utility.no-data></x-utility.no-data>
        @endif


    </div>

</x-layout>
