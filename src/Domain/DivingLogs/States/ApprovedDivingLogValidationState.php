<?php

namespace Domain\DivingLogs\States;

class ApprovedDivingLogValidationState extends DivingLogValidationState
{
    public function name(): string
    {
        return 'Approved';
    }

    public function isPending(): bool
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

}
