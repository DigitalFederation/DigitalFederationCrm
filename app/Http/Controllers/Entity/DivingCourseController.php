<?php

namespace App\Http\Controllers\Entity;

use App\Http\Controllers\Controller;
use Domain\Entities\Models\Entity;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DivingCourseController extends Controller
{
    /**
     * Display the diving course management page for the entity.
     */
    public function index(): View
    {
        // Attempt to get the entity associated with the logged-in user
        // Adapt this logic if the entity comes from a route parameter or other source
        $user = Auth::user();
        if (! $user || ! $user->hasRole('entity-admin')) {
            abort(403, 'Unauthorized action.');
        }

        // Assuming entity-admin is associated with one primary entity
        $entity = $user->entities()->first();

        if (! $entity) {
            abort(404, 'Entity not found for the current user.');
        }

        return view('web.entity.diving_course.index', compact('entity'));
    }
}
