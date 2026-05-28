<x-layout>
    <div class="previous-layout-classes">

        <!-- Page header -->
        <div class="mb-8 flex justify-between">
            <!-- Title -->
            <h1 class="page-first-title">{{ __('Edit Product') }}</h1>
        </div>

        <form action="{{ route(Request::segment(1).'.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('web.admin.products.form', ['button_label' => __('Update Record')])
        </form>
    </div>
</x-layout>
