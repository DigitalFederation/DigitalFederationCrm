<?php

namespace Domain\DivingLogs\States;

class DraftDivingLogState extends DivingLogState
{
    public function name(): string
    {
        return 'draft';
    }

    public function isDraft(): bool
    {
        return true;
    }

    public function isApproved(): bool
    {
        return false;
    }

    public function color()
    {
        return 'draft-state';
    }

    public function svg(): string
    {
        return '';
    }
}
