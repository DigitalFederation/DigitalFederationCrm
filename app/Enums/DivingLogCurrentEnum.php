<?php

namespace App\Enums;

enum DivingLogCurrentEnum: string
{
    case NoCurrent = 'NoCurrent';
    case LightCurrent = 'LightCurrent';
    case StrongCurrent = 'StrongCurrent';
    case RippingCurrent = 'RippingCurrent';

    public function toString(): string
    {
        return match ($this) {
            self::NoCurrent => 'No Current',
            self::LightCurrent => 'Light Current',
            self::StrongCurrent => 'Strong Current',
            self::RippingCurrent => 'Ripping Current',
        };
    }
}
