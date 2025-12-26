<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case SUCCESSFUL = 'successful';
    case FAILED = 'Failed';
    case REFUNDED = 'Refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::SUCCESSFUL => 'Paid',
            self::FAILED => 'Failed',
            self::REFUNDED => 'Refunded',
        };
    }

    
    
}



