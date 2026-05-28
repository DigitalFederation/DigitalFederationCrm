<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="mb-8 flex justify-between">
            <!-- Title -->
            <h1 class="page-first-title">{{ __('Add Product') }}</h1>
        </div>

        <form action="{{ route(Request::segment(1).'.products.store') }}" method="POST">
            @csrf
            @include('web.admin.products.form', ['button_label' => __('Save Record')])
        </form>
    </div>
</x-layout>
