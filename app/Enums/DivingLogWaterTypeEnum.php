<?php

namespace App\Enums;

enum DivingLogWaterTypeEnum: string
{
    case SaltWater = 'SaltWater';
    case FreshWater = 'FreshWater';

    public function toString(): string
    {
        return match ($this) {
            self::SaltWater => 'Salt Water',
            self::FreshWater => 'Fresh Water',
        };
    }
}
