<?php

declare(strict_types=1);

namespace Domain\DivingLogs\Actions;

use Domain\DivingLogs\Models\DivingCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeleteDivingCourseAction
{
    public function __invoke(DivingCourse $divingCourse): bool
    {
        // Optional: Add authorization check here
        $id = $divingCourse->id; // Store id for logging
        $deleted = $divingCourse->delete();

        if ($deleted) {
            Log::info('DivingCourse deleted', [
                'id' => $id,
                'user_id' => Auth::id(),
            ]);
        }

        return $deleted;
    }
}
