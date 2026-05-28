<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Domain\Certifications\Models\Certification;
use Domain\DivingLogs\Actions\CreateDivingCourseAction;
use Domain\DivingLogs\Actions\DeleteDivingCourseAction;
use Domain\DivingLogs\Actions\UpdateDivingCourseAction;
use Domain\DivingLogs\DataTransferObject\DivingCourseData;
use Domain\DivingLogs\Models\DivingCourse;
use Domain\Entities\Models\Entity;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DivingCourseController extends Controller
{
    public function index(): View
    {
        $divingCourses = DivingCourse::with(['entity', 'certification'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('web.admin.diving_course.index', compact('divingCourses'));
    }

    public function create(): View
    {
        $entities = Entity::orderBy('name')->get();
        $certifications = Certification::orderBy('name')->get();

        return view('web.admin.diving_course.create', compact('entities', 'certifications'));
    }

    public function store(Request $request, CreateDivingCourseAction $createAction): RedirectResponse
    {
        $validated = $request->validate([
            'entity_id' => ['required', 'exists:entity,id'],
            'certification_id' => ['required', 'exists:certification,id'],
            'start_date' => ['nullable', 'date'],
            'about' => ['nullable', 'string', 'max:65535'],
        ]);
        try {
            $createAction(DivingCourseData::fromArray($validated));
        } catch (Exception $e) {
            Log::error('Error creating DivingCourse: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', __('Error creating Diving Course.'));
        }

        return redirect()->route('admin.diving-course.index')->with('success', __('Diving Course created successfully.'));
    }

    public function edit(int $id): View
    {
        $divingCourse = DivingCourse::findOrFail($id);
        $entities = Entity::orderBy('name')->get();
        $certifications = Certification::orderBy('name')->get();

        return view('web.admin.diving_course.edit', compact('divingCourse', 'entities', 'certifications'));
    }

    public function update(Request $request, int $id, UpdateDivingCourseAction $updateAction): RedirectResponse
    {
        $divingCourse = DivingCourse::findOrFail($id);
        $validated = $request->validate([
            'entity_id' => ['required', 'exists:entity,id'],
            'certification_id' => ['required', 'exists:certification,id'],
            'start_date' => ['nullable', 'date'],
            'about' => ['nullable', 'string', 'max:65535'],
        ]);
        try {
            $updateAction($divingCourse, DivingCourseData::fromArray($validated));
        } catch (Exception $e) {
            Log::error('Error updating DivingCourse: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', __('Error updating Diving Course.'));
        }

        return redirect()->route('admin.diving-course.index')->with('success', __('Diving Course updated successfully.'));
    }

    public function destroy(int $id, DeleteDivingCourseAction $deleteAction): RedirectResponse
    {
        $divingCourse = DivingCourse::findOrFail($id);
        try {
            $deleteAction($divingCourse);
        } catch (Exception $e) {
            Log::error('Error deleting DivingCourse: ' . $e->getMessage());

            return redirect()->back()->with('error', __('Error deleting Diving Course.'));
        }

        return redirect()->route('admin.diving-course.index')->with('success', __('Diving Course deleted successfully.'));
    }
}
