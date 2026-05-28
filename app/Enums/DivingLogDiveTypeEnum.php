<?php

namespace App\Enums;

enum DivingLogDiveTypeEnum: int
{
    case Diving = 1;
    case Freediving = 2;
    case ExtendedRange = 3;
    case RebreatherCcr = 4;
    case RebreatherScr = 5;

    public function toString(): string
    {
        return match ($this) {
            self::Diving => 'Diving',
            self::Freediving => 'Freediving',
            self::ExtendedRange => 'Extended Range',
            self::RebreatherCcr => 'Rebreather | Closed Circuit Rebreather (CCR)',
            self::RebreatherScr => 'Rebreather | Semi-Closed Rebreather (SCR)',
        };
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_combine(self::values(), self::names());
    }
}
