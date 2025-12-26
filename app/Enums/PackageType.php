<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum PackageType: string
{   use EnumHelpers;
    case LOCAL = 'local';
    case INTERNATIONAL = 'international';

    public function label(): string
    {
        return match ($this) {
            self::LOCAL => 'Local',
            self::INTERNATIONAL => 'International',
            
        };
    }
}