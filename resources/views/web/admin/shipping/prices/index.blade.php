<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="page-first-title">{{ __('Shipping Prices') }}</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a class="btn btn-primary" href="{{ route('admin.shipping.prices.create') }}">
                    <span>{{ __('Add Shipping Price') }}</span>
                </a>
            </div>
        </div>



        <!-- FILTER RESULTS COUNT -->
        <div class="sm:flex flex-row gap-4">
            <x-utility.card-total title="Weights" :count="$prices->total()"></x-utility.card-total>
            <!-- FILTER RESULTS -->
            <x-filter-form :post="route('admin.shipping.prices.index')">

            </x-filter-form>
        </div>

        @if(!empty($prices) && $prices->isNotEmpty())
            <div class="sm:flex sm:justify-center sm:items-center mb-5">
                <!-- Table -->
                <x-dynamic-table
                    :headers="['Method', 'Zone', 'Weight','Price','Actions']">
                    @foreach($prices as $price)
                        <tr>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                {{ optional($price->shippingMethod)->name }}
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                {{ optional($price->shippingZone)->name }}
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                {{ optional($price->shippingWeight)->minumum_weight }} > {{ optional($price->shippingWeight)->maximum_weight }}
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                {{ $price->price }}
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 w-px">
                                <div class="gap-x-2 flex justify-end">

                                    <x-dynamic-table-buttons type="edit" :route="route('admin.shipping.prices.edit', $price->id)"/>
                                    <x-dynamic-table-buttons type="delete" :route="route('admin.shipping.prices.destroy', $price->id)" method="DELETE"/>

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
            {{$prices->links()}}
        </div>

    </div>
</x-layout>
