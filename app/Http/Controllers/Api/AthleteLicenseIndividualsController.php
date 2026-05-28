<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Domain\Individuals\Models\Individual;
use Domain\Licenses\States\ActiveLicenseAttributedState;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AthleteLicenseIndividualsController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'dob' => 'required|date_format:Y-m-d',
        ]);

        $dob = Carbon::createFromFormat('Y-m-d', $request->dob);

        $query = Individual::query();

        $query->whereDate('birthdate', '=', $dob)
            ->where('name', 'like', '%' . $request->firstname . '%')
            ->where('surname', 'like', '%' . $request->lastname . '%');

        // Filter by Family Name if provided
        if ($request->has('dob')) {
            $query->where('birthdate', $request->dob);
        }

        $athletes = $query->with(['licenses.license.professionalRole', 'country']) // Eager load related models
            ->whereHas('licenses', function ($query) {
                $query->where('status_class', ActiveLicenseAttributedState::class)
                    ->whereHas('license', function ($query) {
                        $query->whereHas('professionalRole', function ($query) {
                            $query->where('role', 'like', 'ATHLETE');
                        });
                    });
            })
            ->get()
            ->map(function ($individual) {
                return [
                    'licenses' => $individual->licenses
                        ->where('status_class', ActiveLicenseAttributedState::class)
                        ->map(fn ($license) => $license->license_name)->values()->all(), // Ensure it's reindexed
                    'licenseexpiry' => $individual->licenses
                        ->where('status_class', ActiveLicenseAttributedState::class)
                        ->map(fn ($license) => $license->date_expire ?? 'Not Available') // Provide a default value if null
                        ->values()->all(), // Ensure it's reindexed
                    'firstname' => $individual->name,
                    'lastname' => $individual->surname,
                    'countrycode' => $individual->country ? $individual->country->iso : null,
                ];
            });

        return response()->json($athletes);
    }
}
