<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\DivingLocationRequest;
use Domain\DivingLogs\Models\DivingLocation;
use Domain\DivingLogs\Models\DivingLog;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class BaseDivingLocationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(DivingLocationRequest $request): RedirectResponse
    {
        // Save the diving location
        try {
            $divingLocation = DivingLocation::create($request->validated());

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $divingLocation->addMediaFromRequest('image')->toMediaCollection('diving-location-images');
            }
        } catch (Exception $ex) {
            Log::error($ex);

            return redirect()->back()->with('error', __('diving_location.create_error', ['error' => $ex->getMessage()]));
        }

        return redirect()->back()->with('success', __('diving_location.created_successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DivingLocationRequest $request, int $id): RedirectResponse
    {
        $divingLocation = DivingLocation::findOrFail($id);

        try {
            $divingLocation->update($request->validated());

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Clear existing image if a new one is uploaded
                $divingLocation->clearMediaCollection('diving-location-images');
                $divingLocation->addMediaFromRequest('image')->toMediaCollection('diving-location-images');
            } elseif ($request->boolean('remove_image')) {
                $divingLocation->clearMediaCollection('diving-location-images');
            }

        } catch (Exception $ex) {
            Log::error($ex);

            return redirect()->back()->with('error', __('diving_location.update_error', ['error' => $ex->getMessage()]));
        }

        return redirect()->back()->with('success', __('diving_location.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        if (! DivingLog::where('diving_location_id', $id)->exists()) {
            DivingLocation::destroy($id);

            return redirect()->back()->with('success', __('diving_location.deleted_successfully'));
        } else {
            return redirect()->back()->with('error', __('diving_location.delete_error_in_use'));
        }
    }
}
