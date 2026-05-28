<?php

namespace App\Http\Controllers\Individual;

use App\Http\Controllers\Common\BaseDivingLocationController;
use App\Models\Country;
use Domain\DivingLogs\Models\DivingLocation;
use Domain\Entities\Models\Entity;
use Domain\Federations\Models\Federation;
use Domain\Individuals\Models\Individual;
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
        $divingLocations = DivingLocation::with('country', 'owner')->individualSearch()->paginate();

        return view('web.individual.diving_location.index', compact('divingLocations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $countries = Country::orderBy('name')->pluck('name', 'id');
        $user = Auth::user();
        $country = Country::find($user->country_id);
        $divingLocation = new DivingLocation;
        $individual = auth()->user()->individual;

        if (! empty($individual->country_id)) {
            $country = Country::find($individual->country_id);
            $divingLocation->lat = $country->lat;
            $divingLocation->lng = $country->lng;
            $divingLocation->country_id = $country->id; // Assign country_id if found
        }

        // Fetch locations owned by this Individual
        $existingLocations = DivingLocation::where('owner_type', Individual::class)
            ->where('owner_id', $individual->id)
            ->get(['id', 'name', 'lat', 'lng']);

        // Fetch locations owned by other Individuals, all Entities, and all Federations
        $publicLocations = DivingLocation::where(function ($query) use ($individual) {
            $query->where('owner_type', Individual::class)
                ->where('owner_id', '!=', $individual->id); // Other individuals
        })->orWhere(function ($query) {
            $query->where('owner_type', Entity::class); // All entities
        })->orWhere(function ($query) {
            $query->where('owner_type', Federation::class); // All federations
        })
            ->get(['id', 'name', 'lat', 'lng']);

        return view('web.individual.diving_location.create', [
            'countries' => $countries,
            'divingLocation' => $divingLocation,
            'existingLocations' => $existingLocations,
            'publicLocations' => $publicLocations,
        ]);
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
        $divingLocation = DivingLocation::where('owner_type', Individual::class)->where('owner_id', auth()->user()->individuals->first()->id)->FindOrFail($id);

        $countries = Country::select('id', 'name')->orderBy('name')->get()->pluck('name', 'id');

        return view('web.individual.diving_location.edit', compact('countries', 'divingLocation'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $individual = auth()->user()->individuals()->first();

        // Get the diving location and check ownership
        $location = DivingLocation::where('id', $id)
            ->where('owner_type', Individual::class)
            ->where('owner_id', $individual->id)
            ->firstOrFail();

        // Proceed with deletion
        $location->delete();

        return redirect()->back()->with('success', 'Spot deleted with success.');
    }
}
