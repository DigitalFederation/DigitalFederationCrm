<?php

namespace App\Enums;

enum DivingLogEntryEnum: string
{
    case ShoreBeach = 'ShoreBeach';
    case BoatDive = 'BoatDive';
    case Other = 'Other';

    public function toString(): string
    {
        return match ($this) {
            self::ShoreBeach => 'Shore / Beach',
            self::BoatDive => 'Boat Dive',
            self::Other => 'Other',
        };
    }
}
