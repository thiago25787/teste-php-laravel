<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

Enum CategoryEnum: int
{
    use EnumToArrayTrait;

    case REMESSA = 1;
    case REMESSA_PARCIAL = 2;

    public function name(): string
    {
        return match ($this)
        {
            self::REMESSA => 'Remessa',
            self::REMESSA_PARCIAL => 'Remessa Parcial',
        };
    }
}
