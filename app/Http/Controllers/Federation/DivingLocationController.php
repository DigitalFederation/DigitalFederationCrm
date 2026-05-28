<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\Common\BaseDivingLocationController;
use Domain\DivingLogs\Models\DivingLocation;
use Domain\Entities\Models\Entity;
use Domain\Federations\Actions\GetCountriesByFederationAction;
use Domain\Federations\Models\Federation;
use Domain\Users\Actions\GetUserTypeAction;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DivingLocationController extends BaseDivingLocationController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $divingLocations = DivingLocation::with('country', 'owner')->whereHas('owner', function (Builder $query) {
            return $query->where('id', GetUserTypeAction::execute(\Illuminate\Support\Facades\Auth::user())->id);
        })->paginate();

        return view('web.federation.diving_location.index', compact('divingLocations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $federation = GetUserTypeAction::execute(Auth::user());
        $countries = GetCountriesByFederationAction::execute();
        $country = $countries->first();

        $divingLocation = new DivingLocation;
        $divingLocation->country_id = $country->id;
        $divingLocation->lat = $country->lat;
        $divingLocation->lng = $country->lng;

        // Fetch locations owned by this Federation
        $existingLocations = DivingLocation::where('owner_type', Federation::class)
            ->where('owner_id', $federation->id)
            ->get(['id', 'name', 'lat', 'lng']);

        // Fetch locations owned by other Federations and all Entities
        $publicLocations = DivingLocation::where(function ($query) use ($federation) {
            $query->where('owner_type', Federation::class)
                ->where('owner_id', '!=', $federation->id); // Other federations
        })->orWhere(function ($query) {
            $query->where('owner_type', Entity::class); // All entities
        })
            ->get(['id', 'name', 'lat', 'lng']);

        return view('web.federation.diving_location.create', [
            'countries' => $countries->pluck('name', 'id'),
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
        $divingLocation = DivingLocation::whereHas('owner', function (Builder $query) {
            return $query->where('id', GetUserTypeAction::execute(\Illuminate\Support\Facades\Auth::user())->id);
        })->findOrFail($id);
        $countries = GetCountriesByFederationAction::execute()->pluck('name', 'id');

        return view('web.federation.diving_location.edit', compact('countries', 'divingLocation'));
    }

    /**
     * Remove the specified diving location from storage.
     * Federation can delete their own locations or locations added by their affiliated entities.
     */
    public function destroy(int $id): RedirectResponse
    {
        $federation = GetUserTypeAction::execute(\Illuminate\Support\Facades\Auth::user());

        // Get the diving location
        $divingLocation = DivingLocation::with('owner')->findOrFail($id);

        // Debug information to help diagnose the issue
        $ownerInfo = [
            'federation_id' => $federation->id,
            'federation_class' => get_class($federation),
            'location_owner_id' => $divingLocation->owner_id,
            'location_owner_type' => $divingLocation->owner_type,
        ];

        \Illuminate\Support\Facades\Log::info('Delete permission check', $ownerInfo);

        // Check if the location is owned by the federation or by an affiliated entity
        $canDelete = false;

        // Check if federation owns this location directly - compare both class name and ID
        if ($divingLocation->owner_type === get_class($federation) && (string) $divingLocation->owner_id === (string) $federation->id) {
            $canDelete = true;
            \Illuminate\Support\Facades\Log::info('Federation owns this location directly');
        }
        // Check if the location is owned by an entity affiliated with this federation
        elseif (class_exists($divingLocation->owner_type) && is_a($divingLocation->owner_type, \Domain\Entities\Models\Entity::class, true)) {
            // Get the entity
            $entity = \Domain\Entities\Models\Entity::find($divingLocation->owner_id);

            if ($entity) {
                // Check if entity belongs to this federation
                $entityBelongsToFederation = $entity->federations()
                    ->where('federation_id', $federation->id)
                    ->exists();

                \Illuminate\Support\Facades\Log::info('Entity federation check', [
                    'entity_id' => $entity->id,
                    'belongs_to_federation' => $entityBelongsToFederation,
                ]);

                $canDelete = $entityBelongsToFederation;
            }
        }

        if (! $canDelete) {
            return redirect()->back()->with('error', 'You do not have permission to delete this diving location.');
        }

        // Proceed with deletion
        $divingLocation->delete();

        return redirect()->back()->with('success', 'Diving location deleted successfully.');
    }
}
