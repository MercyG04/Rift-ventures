<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'amount',          
        'currency',        
        'status',          
        'provider',        
        'transaction_id',  
        'idempotency_key', 
    ];

    protected $casts = [
        'status' => PaymentStatus::class, 
    ];

    /**
     * Link to the booking this payment is for.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}


