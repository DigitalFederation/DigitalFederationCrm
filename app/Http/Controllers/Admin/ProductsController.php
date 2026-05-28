<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Domain\Products\DataTransferObject\ProductData;
use Domain\Products\Models\Product;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::scope('name'),
            ])
            ->paginate()
            ->appends(request()->query());

        return view('web.admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('web.admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        try {
            $product = ProductData::fromArray($request->validated());
            $product = ProductData::toModel($product);
            $product->save();

        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->input());
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $product = Product::findOrFail($id);

        return view('web.admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        try {
            $product->update(ProductData::fromArray($request->validated())->toArray());

        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->input());
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        try {
            $product->delete();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }
}
