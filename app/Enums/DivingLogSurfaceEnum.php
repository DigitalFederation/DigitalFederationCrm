<?php

namespace App\Enums;

enum DivingLogSurfaceEnum: string
{
    case Calm = 'Calm';
    case Moving = 'Moving';
    case Storm = 'Storm';

    public function toString(): string
    {
        return match ($this) {
            self::Calm => 'Calm',
            self::Moving => 'Moving',
            self::Storm => 'Storm',
        };
    }
}
