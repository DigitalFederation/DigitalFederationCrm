<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Domain\Shipping\Models\ShippingSubZone;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ShippingSubZoneController extends Controller
{
    /**
     * Display a listing of the shipping zones.
     */
    public function index(): View
    {

        $zones = QueryBuilder::for(ShippingSubZone::class)
            ->allowedFilters([
                AllowedFilter::scope('name'),
            ])
            ->with('country')
            ->paginate()
            ->appends(request()->query());

        return view('web.admin.shipping.sub_zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new shipping zone.
     */
    public function create(): View
    {
        // Get countries to use in the form
        $countries = Country::all();

        return view('web.admin.shipping.sub_zones.create', compact('countries'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Define your validation rules here
        $rules = [
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:country,id',
        ];

        // Create a validator instance and validate the request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // If validation passes, continue to create the sub-zone
        $subZoneData = $validator->validated();

        // Create the sub-zone
        $subZone = ShippingSubZone::create($subZoneData);

        // Redirect with a success message
        return redirect()->route('admin.shipping.sub-zones.index')->with('success', 'Shipping sub-zone created successfully.');
    }

    public function edit(ShippingSubZone $subZone): View
    {
        $countries = Country::all();

        return view('web.admin.shipping.sub_zones.edit', compact('subZone', 'countries'));
    }

    public function update(Request $request, ShippingSubZone $subZone): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:country,id',
        ]);

        $subZone->update($validatedData);

        return redirect()->route('admin.shipping.sub-zones.index')
            ->with('success', 'Shipping sub-zone updated successfully.');
    }

    public function destroy(ShippingSubZone $subZone): RedirectResponse
    {
        $subZone->delete();

        return redirect()->route('admin.shipping.sub-zones.index')
            ->with('success', 'Shipping sub-zone deleted successfully.');
    }
}
