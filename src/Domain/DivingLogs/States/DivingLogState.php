<?php

namespace Domain\DivingLogs\States;

use Domain\DivingLogs\Models\DivingLog;

abstract class DivingLogState
{
    protected DivingLog $divingLog;

    public function __construct(DivingLog $divingLog)
    {
        $this->divingLog = $divingLog;
    }

    abstract public function name();

    abstract public function isDraft();

    abstract public function isApproved();

    abstract public function color();

    abstract public function svg();
}
