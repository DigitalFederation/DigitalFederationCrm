<?php

declare(strict_types=1);

namespace Domain\DivingLogs\Actions;

use Domain\DivingLogs\DataTransferObject\DivingCourseData;
use Domain\DivingLogs\Models\DivingCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateDivingCourseAction
{
    public function __invoke(DivingCourse $divingCourse, DivingCourseData $divingCourseData): bool
    {
        // Optional: Add authorization check here, e.g., ensure Auth::user() owns $divingCourse or is CMAS admin

        $updated = $divingCourse->update([
            'entity_id' => $divingCourseData->entity_id,
            'name' => $divingCourseData->name,
            'certification_system' => $divingCourseData->certification_system,
            'district_id' => $divingCourseData->district_id,
            'location' => $divingCourseData->location,
            'certification_id' => $divingCourseData->certification_id,
            'start_date' => $divingCourseData->start_date,
            'about' => $divingCourseData->about,
        ]);

        if ($updated) {
            Log::info('DivingCourse updated', [
                'id' => $divingCourse->id,
                'user_id' => Auth::id(),
            ]);
        }

        return $updated;
    }
}
