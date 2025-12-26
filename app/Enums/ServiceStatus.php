<?php

namespace App\Enums;

enum ServiceStatus: string
{
    case PENDING = 'pending';       
    case REVIEWED = 'contacted';  
    case INVOICED = 'invoiced';         
    case COMPLETED = 'completed'; 
    
    case CANCELLED = 'cancelled';  
    

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::REVIEWED => 'Reviewed',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    
}