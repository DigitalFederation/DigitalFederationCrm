<?php

declare(strict_types=1);

namespace Domain\DivingLogs\Actions;

use Domain\DivingLogs\DataTransferObject\DivingCourseData;
use Domain\DivingLogs\Models\DivingCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateDivingCourseAction
{
    public function __invoke(DivingCourseData $divingCourseData): DivingCourse
    {
        // Optional: Add authorization check here if needed, e.g., check if Auth::user() can manage courses for $divingCourseData->entity_id

        $divingCourse = DivingCourse::create([
            'entity_id' => $divingCourseData->entity_id,
            'name' => $divingCourseData->name,
            'certification_system' => $divingCourseData->certification_system,
            'district_id' => $divingCourseData->district_id,
            'location' => $divingCourseData->location,
            'certification_id' => $divingCourseData->certification_id,
            'start_date' => $divingCourseData->start_date,
            'about' => $divingCourseData->about,
        ]);

        // Optional: Logging
        Log::info('DivingCourse created', [
            'id' => $divingCourse->id,
            'entity_id' => $divingCourse->entity_id,
            'location_id' => $divingCourse->diving_location_id,
            'certification_id' => $divingCourse->certification_id,
            'user_id' => Auth::id(),
        ]);

        return $divingCourse;
    }
}
