<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="page-first-title">{{ __('Shipping Weights') }}</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a class="btn btn-primary" href="{{ route('admin.shipping.weights.create') }}">
                    <span>{{ __('Add weight range') }}</span>
                </a>
            </div>
        </div>



        <!-- FILTER RESULTS COUNT -->
        <div class="sm:flex flex-row gap-4">
            <x-utility.card-total title="Weights" :count="$weights->total()"></x-utility.card-total>
            <!-- FILTER RESULTS -->
            <x-filter-form :post="route('admin.shipping.weights.index')">
                <x-forms.filter-input-text label="Range" name="range"/>
            </x-filter-form>
        </div>

        @if(!empty($weights) && $weights->isNotEmpty())
            <div class="sm:flex sm:justify-center sm:items-center mb-5">
                <!-- Table -->
                <x-dynamic-table
                    :headers="['Method', 'Range','Min','Max','Actions']">
                    @foreach($weights as $weight)
                        <tr>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                {{ optional($weight->shippingMethod)->name }}
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                {{ $weight->range }}
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                {{ $weight->minimum_weight }}
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                {{ $weight->maximum_weight }}
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 w-px">
                                <div class="gap-x-2 flex justify-end">

                                    <x-dynamic-table-buttons type="edit" :route="route('admin.shipping.weights.edit', $weight->id)"/>
                                    <x-dynamic-table-buttons type="delete" :route="route('admin.shipping.weights.destroy', $weight->id)" method="DELETE"/>

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
            {{$weights->links()}}
        </div>

    </div>
</x-layout>
