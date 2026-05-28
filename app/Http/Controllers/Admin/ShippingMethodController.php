<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingMethodRequest;
use Domain\Shipping\Models\ShippingMethod;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ShippingMethodController extends Controller
{
    /**
     * Display a listing of the shipping.
     *
     * @return View
     */
    public function index()
    {
        $methods = ShippingMethod::all();

        return view('web.admin.shipping.methods.index', compact('methods'));
    }

    /**
     * Show the form for creating a new shipping.
     *
     * @return View
     */
    public function create()
    {
        return view('web.admin.shipping.methods.create');
    }

    /**
     * Store a newly created shipping in storage.
     *
     * @return RedirectResponse
     */
    public function store(ShippingMethodRequest $request)
    {
        ShippingMethod::create($request->validated());

        return redirect()->route('admin.shipping.methods.index')->with('success', 'Shipping method created successfully.');
    }

    /**
     * Show the form for editing the specified shipping.
     *
     * @return View
     */
    public function edit(ShippingMethod $method)
    {
        return view('cmas.shipping.methods.edit', compact('method'));
    }

    /**
     * Update the specified shipping in storage.
     *
     * @return RedirectResponse
     */
    public function update(ShippingMethodRequest $request, ShippingMethod $method)
    {
        $method->update($request->validated());

        return redirect()->route('admin.shipping.methods.index')->with('success', 'Shipping method updated successfully.');
    }

    /**
     * Remove the specified shipping method from storage.
     *
     * @return RedirectResponse
     */
    public function destroy(ShippingMethod $method)
    {
        $method->delete();

        return redirect()->route('admin.shipping.methods.index')->with('success', 'Shipping method deleted successfully.');
    }
}
