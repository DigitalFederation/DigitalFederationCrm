<?php

namespace Domain\DivingLogs\States;

class ApprovedDivingLogState extends DivingLogState
{
    public function name(): string
    {
        return 'confirmed';
    }

    public function isDraft(): bool
    {
        return false;
    }

    public function isApproved(): bool
    {
        return true;
    }

    public function color(): string
    {
        return 'active-state';
    }

    public function svg(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"                    stroke="currentColor" class="w-4 h-4 text-white">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>';
    }
}
