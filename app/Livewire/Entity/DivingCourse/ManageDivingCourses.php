<?php

declare(strict_types=1);

namespace App\Livewire\Entity\DivingCourse;

use Domain\DivingLogs\Actions\CreateDivingCourseAction;
use Domain\DivingLogs\Actions\DeleteDivingCourseAction;
use Domain\DivingLogs\Actions\UpdateDivingCourseAction;
use Domain\DivingLogs\DataTransferObject\DivingCourseData;
use Domain\DivingLogs\Models\DivingCourse;
use Domain\Entities\Models\Entity;
use Domain\Geographic\Models\District;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageDivingCourses extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;
    use WithPagination;

    public Entity $entity;

    public ?DivingCourse $editing = null;

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $confirmingDelete = false;

    public ?int $deleteId = null;

    // Form fields
    public ?string $name = '';

    public ?string $certification_system = null;

    public ?int $district_id = null;

    public ?string $location = null;

    public ?string $start_date = null;

    public ?string $about = '';

    public $courseImage = null;

    public array $districts = [];

    public function mount(): void
    {
        $user = Auth::user();
        if (! $user) {
            abort(403, 'User not authenticated.');
        }

        $hasRole = $user->hasAnyRole(['entity-admin', 'entity-diving-services']);
        $entity = $user->entities()->first();

        if (! $hasRole) {
            abort(403, 'Unauthorized action.');
        }

        if (! $entity) {
            abort(403, 'User is not associated with any entity.');
        }

        $this->entity = $entity;
        $this->loadDistricts();
    }

    private function loadDistricts(): void
    {
        $this->districts = District::query()
            ->active()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('entity_diving_courses')
                    ->where('entity_id', $this->entity->id)
                    ->where('name', $this->name)
                    ->when($this->start_date, fn ($query) => $query->where('start_date', $this->start_date))
                    ->when(! $this->start_date, fn ($query) => $query->whereNull('start_date'))
                    ->ignore($this->editing?->id),
            ],
            'certification_system' => ['nullable', 'string', Rule::in(array_keys(DivingCourse::CERTIFICATION_SYSTEMS))],
            'district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'about' => ['nullable', 'string', 'max:65535'],
            'courseImage' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function save(CreateDivingCourseAction $createAction): void
    {
        $this->authorize('create', [DivingCourse::class, $this->entity]);
        $validatedData = $this->validate();
        $validatedData['entity_id'] = $this->entity->id;

        $course = $createAction(DivingCourseData::fromArray($validatedData));

        // Handle image upload
        if ($this->courseImage) {
            $course->addMedia($this->courseImage->getRealPath())
                ->usingFileName(pathinfo($this->courseImage->getClientOriginalName(), PATHINFO_FILENAME) . '_' . uniqid() . '.' . $this->courseImage->getClientOriginalExtension())
                ->toMediaCollection('course-image');
        }

        $this->showCreateModal = false;
        $this->resetForm();

        Notification::make()
            ->title(__('diving_courses.course_added'))
            ->success()
            ->send();
    }

    public function edit(DivingCourse $divingCourse): void
    {
        $this->authorize('update', $divingCourse);
        $this->resetValidation();
        $this->editing = $divingCourse;
        $this->name = $divingCourse->name ?? $divingCourse->certification?->name ?? '';
        $this->certification_system = $divingCourse->certification_system;
        $this->district_id = $divingCourse->district_id;
        $this->location = $divingCourse->location;
        $this->start_date = $divingCourse->start_date?->format('Y-m-d');
        $this->about = $divingCourse->about;
        $this->courseImage = null;
        $this->showEditModal = true;
    }

    public function update(UpdateDivingCourseAction $updateAction): void
    {
        if (! $this->editing) {
            return;
        }

        $this->authorize('update', $this->editing);
        $validatedData = $this->validate();
        $validatedData['entity_id'] = $this->entity->id;

        $updateAction($this->editing, DivingCourseData::fromArray($validatedData));

        // Handle image upload
        if ($this->courseImage) {
            $this->editing->clearMediaCollection('course-image');
            $this->editing->addMedia($this->courseImage->getRealPath())
                ->usingFileName(pathinfo($this->courseImage->getClientOriginalName(), PATHINFO_FILENAME) . '_' . uniqid() . '.' . $this->courseImage->getClientOriginalExtension())
                ->toMediaCollection('course-image');
        }

        $this->showEditModal = false;
        $this->editing = null;
        $this->resetForm();

        Notification::make()
            ->title(__('diving_courses.course_updated'))
            ->success()
            ->send();
    }

    public function removeImage(): void
    {
        if ($this->editing) {
            $this->editing->clearMediaCollection('course-image');

            Notification::make()
                ->title(__('diving_courses.image_removed'))
                ->success()
                ->send();
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete(DeleteDivingCourseAction $deleteAction): void
    {
        if ($this->deleteId) {
            $divingCourse = DivingCourse::find($this->deleteId);
            if ($divingCourse) {
                $this->authorize('delete', $divingCourse);
                $deleteAction($divingCourse);
                Notification::make()
                    ->title(__('diving_courses.course_deleted'))
                    ->success()
                    ->send();
            }
        }
        $this->confirmingDelete = false;
        $this->deleteId = null;
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->certification_system = null;
        $this->district_id = null;
        $this->location = null;
        $this->start_date = null;
        $this->about = '';
        $this->courseImage = null;
        $this->editing = null;
    }

    public function render(): View
    {
        $this->authorize('viewAny', [DivingCourse::class, $this->entity]);

        $divingCourses = $this->entity->divingCourses()
            ->with(['certification:id,name', 'district:id,name', 'media'])
            ->orderBy('name')
            ->orderBy('start_date')
            ->paginate(10);

        return view('livewire.entity.diving-course.manage-diving-courses', [
            'divingCourses' => $divingCourses,
        ]);
    }
}
