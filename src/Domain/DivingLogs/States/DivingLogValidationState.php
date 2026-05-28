<?php

namespace Domain\DivingLogs\States;

use Domain\DivingLogs\Models\DivingLogValidation;

abstract class DivingLogValidationState
{
    protected DivingLogValidation $divingLogValidation;

    public function __construct(DivingLogValidation $divingLog)
    {
        $this->divingLogValidation = $divingLog;
    }

    abstract public function name();

    abstract public function isPending();

    abstract public function isApproved();

    abstract public function color();

}
