<?php

namespace App\Enums;

enum CommitteeCodeEnum: string
{
    case Sport = 'SPORT';
    case Diving = 'DIVING';
    case Scientific = 'SCIENTIFIC';
    case DivingServices = 'DIVINGSERVICES';

    public static function divingAndScientificValues(): array
    {
        return [
            self::Diving->value,
            self::Scientific->value,
        ];
    }

    public static function certificationFilterValues(string $code): array
    {
        $code = strtoupper($code);

        return $code === self::Diving->value
            ? self::divingAndScientificValues()
            : [$code];
    }
}
