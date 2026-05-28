<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingPricesRequest;
use Domain\Shipping\Models\ShippingMethod;
use Domain\Shipping\Models\ShippingPrice;
use Domain\Shipping\Models\ShippingWeight;
use Domain\Shipping\Models\ShippingZone;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ShippingPricesController extends Controller
{
    public function index()
    {
        $prices = QueryBuilder::for(ShippingPrice::class)
            ->allowedFilters([
                AllowedFilter::scope('zone'),
            ])
            ->with('shippingMethod', 'shippingZone', 'shippingWeight')
            ->paginate()
            ->appends(request()->query());

        return view('web.admin.shipping.prices.index', compact('prices'));
    }

    public function create()
    {
        $zones = ShippingZone::all();
        $weights = ShippingWeight::all();
        $methods = ShippingMethod::all();

        return view('web.admin.shipping.prices.create', compact('zones', 'weights', 'methods'));
    }

    public function store(ShippingPricesRequest $request)
    {
        // Validate the request using the ShippingPriceRequest
        $validatedData = $request->validated();

        // Create a new ShippingPrice record using the validated data
        $shippingPrice = ShippingPrice::create($validatedData);

        // Redirect to the index page with a success message
        return redirect()->route('admin.shipping.prices.index')
            ->with('success', 'Shipping price created successfully.');
    }

    public function edit(ShippingPrice $price)
    {
        $zones = ShippingZone::all();
        $weights = ShippingWeight::all();
        $methods = ShippingMethod::all();

        // Pass the existing price to the view
        return view('web.admin.shipping.prices.edit', compact('price', 'zones', 'weights', 'methods'));
    }

    public function update(ShippingPricesRequest $request, ShippingPrice $price)
    {
        $validatedData = $request->validated();
        $price->update($validatedData);

        // Redirect to the index page with a success message
        return redirect()->route('admin.shipping.prices.index')
            ->with('success', 'Shipping price updated successfully.');
    }
}
