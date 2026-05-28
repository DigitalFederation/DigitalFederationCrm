<?php

namespace Domain\DivingLogs\States;

class PendingDivingLogState extends DivingLogState
{
    public function name(): string
    {
        return 'unconfirmed';
    }

    public function isDraft(): bool
    {
        return false;
    }

    public function isApproved(): bool
    {
        return false;
    }

    public function color(): string
    {
        return 'pending-state';
    }

    public function svg(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"                  stroke="currentColor" class="w-4 h-4 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>';
    }
}
