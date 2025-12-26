<?php

namespace App\Enums;

enum Currency: string
{
    case USD = 'USD';
    case KES = 'KES';

    
    public function label(): string
    {
        return match($this) {
            self::USD => 'US Dollar ($)',
            self::KES => 'Kenyan Shilling (Ksh)',
        };
    }
}