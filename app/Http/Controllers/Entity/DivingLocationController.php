<?php

namespace App\Http\Controllers\Entity;

use App\Http\Controllers\Common\BaseDivingLocationController;
use App\Models\Country;
use Domain\DivingLogs\Models\DivingLocation;
use Domain\Entities\Models\Entity;
use Domain\Federations\Models\Federation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DivingLocationController extends BaseDivingLocationController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $entity = Auth::user()->entities()->first();
        $federationIds = $entity->federations()->pluck('federation.id')->toArray();

        // Get filter parameters
        $searchName = request('search_name', '');
        $districtId = request('district_id', '');

        $divingLocations = DivingLocation::with('country', 'district', 'owner')
            ->where(function ($query) use ($entity, $federationIds) {
                // Entity's own locations
                $query->where(function ($q) use ($entity) {
                    $q->where('owner_type', Entity::class)
                        ->where('owner_id', $entity->id);
                });

                // Locations from federations the entity belongs to
                if (! empty($federationIds)) {
                    $query->orWhere(function ($q) use ($federationIds) {
                        $q->where('owner_type', Federation::class)
                            ->whereIn('owner_id', $federationIds);
                    });
                }
            })
            // Filter by name
            ->when($searchName, function ($query) use ($searchName) {
                $query->where(function ($q) use ($searchName) {
                    $q->where('name', 'like', "%{$searchName}%")
                        ->orWhere('native_name', 'like', "%{$searchName}%");
                });
            })
            // Filter by district
            ->when($districtId, function ($query) use ($districtId) {
                $query->where('district_id', $districtId);
            })
            ->orderBy('name')
            ->paginate();

        // Get districts for filter dropdown
        $districts = \Domain\Geographic\Models\District::query()
            ->active()
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('web.entity.diving_location.index', compact('divingLocations', 'districts', 'searchName', 'districtId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $countries = Country::select('id', 'name')->orderBy('name')->get()->pluck('name', 'id');
        $districts = \Domain\Geographic\Models\District::query()
            ->active()
            ->orderBy('name')
            ->pluck('name', 'id');
        $entity = Auth::user()->entities->first();
        $country = Country::find($entity->country_id);
        $divingLocation = new DivingLocation;
        $divingLocation->country_id = $country->id;
        $divingLocation->lat = $country->lat;
        $divingLocation->lng = $country->lng;

        $existingLocations = DivingLocation::where('owner_type', Entity::class)
            ->where('owner_id', $entity->id)
            ->get(['id', 'name', 'lat', 'lng']);

        // Fetch locations owned by other Entities and all Federations
        $publicLocations = DivingLocation::where(function ($query) use ($entity) {
            $query->where('owner_type', Entity::class)
                ->where('owner_id', '!=', $entity->id); // Other entities
        })->orWhere(function ($query) {
            $query->where('owner_type', Federation::class); // All federations
        })
            ->get(['id', 'name', 'lat', 'lng']);

        return view('web.entity.diving_location.create', compact('countries', 'districts', 'divingLocation', 'existingLocations', 'publicLocations'));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $divingLocation = DivingLocation::where('owner_type', Entity::class)
            ->where('owner_id', Auth::user()->entities()->first()->id)->findOrFail($id);
        $countries = Country::select('id', 'name')->orderBy('name')->pluck('name', 'id');
        $districts = \Domain\Geographic\Models\District::query()
            ->active()
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('web.entity.diving_location.edit', compact('countries', 'districts', 'divingLocation'));
    }

    /**
     * Remove the specified diving location from storage.
     * Entity can only delete their own diving locations.
     */
    public function destroy(int $id): RedirectResponse
    {
        $entity = Auth::user()->entities()->first();

        // Get the diving location and check ownership
        $divingLocation = DivingLocation::where('id', $id)
            ->where('owner_type', Entity::class)
            ->where('owner_id', $entity->id)
            ->firstOrFail();

        // Proceed with deletion
        $divingLocation->delete();

        return redirect()->back()->with('success', 'Diving location deleted successfully.');
    }
}
