<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="page-first-title">{{ __('Shipping Zones') }}</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <a class="btn btn-info" href="{{ route('admin.shipping.sub-zones.index') }}">
                    <span>{{ __('Shipping Destinations') }}</span>
                </a>

                <a class="btn btn-primary" href="{{ route('admin.shipping.zones.create') }}">
                    <span>{{ __('Add Shipping Zone') }}</span>
                </a>
            </div>
        </div>



            <!-- FILTER RESULTS COUNT -->
            <div class="sm:flex flex-row gap-4">
                <x-utility.card-total title="Zones" :count="$zones->total()"></x-utility.card-total>
                <!-- FILTER RESULTS -->
                <x-filter-form :post="route('admin.shipping.zones.index')">
                    <x-forms.filter-input-text label="Zone name" name="name"/>
                </x-filter-form>
            </div>

            @if(!empty($zones) && $zones->isNotEmpty())
            <div class="sm:flex sm:justify-center sm:items-center mb-5">
                <!-- Table -->
                <x-dynamic-table
                    :headers="['Name', 'Actions']">
                    @foreach($zones as $zone)
                    <tr>
                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                            {{ $zone->name }}
                        </td>
                        <td class="px-2 first:pl-5 last:pr-5 w-px">
                            <div class="gap-x-2 flex justify-end">

                                <x-dynamic-table-buttons type="edit" :route="route('admin.shipping.zones.edit', $zone->id)"/>
                                <x-dynamic-table-buttons type="delete" :route="route('admin.shipping.zones.destroy', $zone->id)" method="DELETE"/>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </x-dynamic-table>
            </div>
        @else
            <x-utility.no-data></x-utility.no-data>
        @endif

        <!-- Pagination -->
        <div class="mt-8">
            {{$zones->links()}}
        </div>

    </div>
</x-layout>
