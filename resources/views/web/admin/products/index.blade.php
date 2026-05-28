<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="page-first-title">{{ __('Products') }}</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <a class="btn btn-primary" href="{{ route('admin.products.create') }}">
                    <span>{{ __('Add Product') }}</span>
                </a>
            </div>
        </div>

        <!-- FILTER RESULTS COUNT -->
        <div class="sm:flex flex-row gap-4">

            <x-utility.card-total title="Products" :count="$products->total()"></x-utility.card-total>

            <!-- FILTER RESULTS -->
            <x-filter-form :post="route('admin.products.index')">
                <x-forms.filter-input-text label="Product name" name="name"/>
            </x-filter-form>
        </div>

        <div class="sm:flex sm:justify-center sm:items-center mb-5">
            <!-- Table -->
            <div class="bg-white shadow-lg rounded-sm border border-slate-200 mb-8 w-full">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <!-- Table header -->
                        <thead
                            class="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-t border-b border-slate-200">
                        <tr>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('Name') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('Code') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('Description') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('Price') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('Tax. (%)') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('Tax (€)') }}</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-left">{{ __('Actions') }}</div>
                            </th>
                        </tr>
                        </thead>
                        <!-- Table body -->
                        <tbody class="text-sm divide-y divide-slate-200">
                        <!-- Row -->
                        @foreach($products as $product)
                            <tr>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    {{ $product->name }}
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    {{ $product->code }}
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px max-w-xs truncate">
                                    {{ $product->description }}
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    {{ $product->price }} €
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    {{ $product->tax_percentage }} %
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    {{ $product->tax_value }} €
                                </td>

                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    <div class="space-x-1 flex">
                                        <a href="{{ route(Request::segment(1).'.products.edit', $product->id) }}"
                                           class="text-slate-400 hover:text-slate-500 rounded-full">
                                            <span class="sr-only">{{ __('Edit') }}</span>
                                            <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                                <path
                                                    d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z"/>
                                            </svg>
                                        </a>
                                        <form class="flex"
                                              action="{{ route(Request::segment(1).'.products.delete', $product->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('{{ __('Are you sure you want to delete this product?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-rose-500 hover:text-rose-600 rounded-full">
                                                <span class="sr-only">{{ __('Delete') }}</span>
                                                <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                                    <path d="M13 15h2v6h-2zM17 15h2v6h-2z"/>
                                                    <path
                                                        d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{$products->links()}}
        </div>

    </div>
</x-layout>
