<?php

namespace App\Enums;

enum DivingLogTankTypeEnum: string
{
    case Steel = 'Steel';
    case Aluminum = 'Aluminum';

    public function toString(): string
    {
        return match ($this) {
            self::Steel => 'Steel',
            self::Aluminum => 'Aluminum',
        };
    }
}
