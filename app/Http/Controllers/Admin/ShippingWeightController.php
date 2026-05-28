<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingWeightRequest;
use Domain\Shipping\Models\ShippingMethod;
use Domain\Shipping\Models\ShippingWeight;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ShippingWeightController extends Controller
{
    public function index()
    {
        $weights = QueryBuilder::for(ShippingWeight::class)
            ->allowedFilters([
                AllowedFilter::scope('range'),
            ])
            ->with('shippingMethod')
            ->paginate()
            ->appends(request()->query());

        return view('web.admin.shipping.weights.index', compact('weights'));
    }

    public function create()
    {
        $shippingMethods = ShippingMethod::select('id', 'name')->get();

        return view('web.admin.shipping.weights.create', compact('shippingMethods'));
    }

    public function store(ShippingWeightRequest $request)
    {
        ShippingWeight::create($request->validated());

        return redirect()->route('admin.shipping.weights.index')->with('success', 'Shipping weight created successfully.');
    }

    public function edit(ShippingWeight $weight)
    {
        $shippingMethods = ShippingMethod::select('id', 'name')->get();

        return view('web.admin.shipping.weights.edit', compact('weight', 'shippingMethods'));
    }

    public function update(ShippingWeightRequest $request, ShippingWeight $weight)
    {
        $weight->update($request->validated());

        return redirect()->route('admin.shipping.weights.index')->with('success', 'Shipping weight updated successfully.');
    }

    public function destroy(ShippingWeight $weight)
    {
        $weight->delete();

        return redirect()->route('admin.shipping.weights.index')->with('success', 'Shipping weight deleted successfully.');
    }
}
