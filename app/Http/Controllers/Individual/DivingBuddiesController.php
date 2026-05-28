<?php

namespace App\Http\Controllers\Individual;

use App\Http\Controllers\Controller;
use App\Http\Requests\DivingBuddiesFormRequest;
use Domain\DivingLogs\Models\DivingBuddy;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class DivingBuddiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        $divingBuddies = DivingBuddy::where('individual_id', auth()->user()->individuals()->first()->id)->paginate(15);

        return view('web.individual.diving_buddy.index', [
            'buddies' => $divingBuddies,
        ]);
    }

    public function store(DivingBuddiesFormRequest $request): RedirectResponse
    {
        try {
            // Validate and get data from the request
            $data = $request->validated();

            $individual = auth()->user()->individuals()->first();

            // Add current individual_id and user_id to the data
            $data['individual_id'] = $individual->id;
            $data['user_id'] = $individual->user_id;

            if ($individual != null && $individual->code_cmas != $data['cmas_code']) {
                // Create a new DivingBuddy using the validated data
                DivingBuddy::create($data);
            } else {
                return redirect()->back()->withInput()->with('error', 'You are registering your self as buddy');
            }

        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->back()->withInput()->with('error', 'Error adding new diving buddy');
        }

        // Redirect the user back to the index page with a success message
        return redirect()->route('individual.diving-buddy.index')->with('success', 'New diving buddy added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DivingBuddy $divingBuddy): View
    {
        return view('web.individual.diving_buddy.edit', ['buddy' => $divingBuddy]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return RedirectResponse
     */
    public function update(DivingBuddiesFormRequest $request, DivingBuddy $divingBuddy)
    {
        $divingBuddy->update($request->validated());

        return redirect()->route('individual.diving-buddy.index')->with('success', 'Diving buddy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return RedirectResponse
     */
    public function destroy(DivingBuddy $divingBuddy)
    {
        if ($divingBuddy->diving_logs()->exists()) {
            return redirect()->route('individual.diving-buddy.index')->with('error', 'Cannot delete diving buddy because it is associated with a diving log.');
        }

        $divingBuddy->delete();

        return redirect()->route('individual.diving-buddy.index')->with('success', 'Diving buddy deleted successfully.');
    }
}
