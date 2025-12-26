<?php

namespace App\Enums;
use App\Traits\EnumHelpers;
enum PackageCategory: string
{ use EnumHelpers;
    case SAFARI = 'safari';
    case DESTINATION = 'destination';
    case GETAWAY = 'getaway';
    case SEASONAL = 'seasonal';

    public function label(): string
    {
        return match ($this) {
            self::SAFARI => 'Safari',
            self::DESTINATION => 'Destination',
            self::GETAWAY => 'Getaway',
            self::SEASONAL => 'Seasonal Deal',
        };
    }
}