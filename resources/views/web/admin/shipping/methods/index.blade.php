<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="page-first-title">{{ __('Shipping Methods') }}</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <a class="btn btn-info" href="{{ route('admin.shipping.prices.index') }}">
                    <span>{{ __('Shipping Prices') }}</span>
                </a>

                <a class="btn btn-info" href="{{ route('admin.shipping.zones.index') }}">
                    <span>{{ __('Shipping Zones') }}</span>
                </a>

                <a class="btn btn-info" href="{{ route('admin.shipping.sub-zones.index') }}">
                    <span>{{ __('Shipping Destinations') }}</span>
                </a>

                <a class="btn btn-info" href="{{ route('admin.shipping.weights.index') }}">
                    <span>{{ __('Shipping Weights') }}</span>
                </a>

                <a class="btn btn-primary" href="{{ route('admin.shipping.methods.create') }}">
                    <span>{{ __('Create Shipping Method') }}</span>
                </a>
            </div>
        </div>


        @if(!empty($methods) && $methods->isNotEmpty())
            <div class="sm:flex sm:justify-center sm:items-center mb-5 mt-5">
                <!-- Table -->
                <x-dynamic-table
                    :headers="['Name','Description', 'Actions']">
                    @foreach($methods as $method)
                        <tr>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                {{ $method->name }}
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                {{ $method->description }}
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 w-px">
                                <div class="gap-x-2 flex justify-end">

                                    <x-dynamic-table-buttons type="edit" :route="route('admin.shipping.methods.edit', $method->id)"/>
                                    <x-dynamic-table-buttons type="delete" :route="route('admin.shipping.methods.destroy', $method->id)" method="DELETE"/>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-dynamic-table>
            </div>
        @else
            <x-utility.no-data></x-utility.no-data>
        @endif


    </div>
</x-layout>
