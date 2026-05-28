<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\BaseDivingLocationController;
use App\Models\Country;
use Domain\DivingLogs\Models\DivingLocation;
use Illuminate\Contracts\View\View;

class DivingLocationController extends BaseDivingLocationController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $divingLocations = DivingLocation::with('country', 'owner')->paginate();

        return view('web.admin.diving_location.index', compact('divingLocations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $countries = Country::select('id', 'name')->orderBy('name')->get()->pluck('name', 'id');
        $divingLocation = new DivingLocation;

        return view('web.admin.diving_location.create', compact('countries', 'divingLocation'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $divingLocation = DivingLocation::findOrFail($id);
        $countries = Country::select('id', 'name')->orderBy('name')->get()->pluck('name', 'id');

        return view('web.admin.diving_location.edit', compact('countries', 'divingLocation'));
    }
}
