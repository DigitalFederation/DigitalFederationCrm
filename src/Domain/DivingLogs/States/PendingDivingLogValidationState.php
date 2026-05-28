<?php

namespace Domain\DivingLogs\States;

class PendingDivingLogValidationState extends DivingLogValidationState
{
    public function name(): string
    {
        return 'Pending';
    }

    public function isPending(): bool
    {
        return true;
    }

    public function isApproved(): bool
    {
        return false;
    }

    public function color(): string
    {
        return 'pending-state';
    }

}
