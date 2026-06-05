<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingZoneRequest;
use Domain\Shipping\Models\ShippingSubZone;
use Domain\Shipping\Models\ShippingZone;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ShippingZoneController extends Controller
{
    /**
     * Display a listing of the shipping zones.
     */
    public function index(): View
    {

        $zones = QueryBuilder::for(ShippingZone::class)
            ->allowedFilters([
                AllowedFilter::scope('name'),
            ])
            ->paginate()
            ->appends(request()->query());

        return view('web.admin.shipping.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new shipping zone.
     */
    public function create(): View
    {
        // Get countries to use in the form
        $subZones = ShippingSubZone::query()->pluck('name', 'id');

        return view('web.admin.shipping.zones.create', compact('subZones'));
    }

    /**
     * Store a newly created shipping zone in storage.
     */
    public function store(ShippingZoneRequest $request): RedirectResponse
    {

        // Validate and store the zone data, excluding the sub_zone_ids
        $validated = $request->validated();
        $subZoneIds = $validated['sub_zones'] ?? []; // assuming 'sub_zones' is the name in the form
        unset($validated['sub_zones']); // Remove the sub_zones key from the array before creating the zone

        $zone = ShippingZone::create($validated);

        // Associate the zone with sub-zones using the attach method
        // The attach method can take an array of IDs, so you don't need a foreach loop
        $zone->subZones()->attach($subZoneIds);

        return redirect()->route('admin.shipping.zones.index')->with('success', 'Shipping zone created successfully.');
    }

    /**
     * Show the form for editing the specified shipping zone.
     */
    public function edit(ShippingZone $zone): View
    {
        $subZones = ShippingSubZone::query()->pluck('name', 'id');

        $zone->load('subZones');

        return view('web.admin.shipping.zones.edit', compact('zone', 'subZones'));
    }

    /**
     * Update the specified shipping zone in storage.
     */
    public function update(ShippingZoneRequest $request, ShippingZone $zone): RedirectResponse
    {
        // Update the zone with validated data, except the sub_zone_ids
        $validated = $request->validated();
        $subZoneIds = $validated['sub_zones'] ?? [];
        unset($validated['sub_zones']); // Remove the sub_zones before updating the zone

        $zone->update($validated);

        // Sync the subzones - this will add new ones and remove unselected ones
        $zone->subZones()->sync($subZoneIds);

        return redirect()->route('admin.shipping.zones.index')->with('success', 'Shipping zone updated successfully.');
    }

    /**
     * Remove the specified shipping zone from storage.
     */
    public function destroy(ShippingZone $zone): RedirectResponse
    {
        $zone->delete();

        return redirect()->route('admin.shipping.zones.index')->with('success', 'Shipping zone deleted successfully.');
    }
}
