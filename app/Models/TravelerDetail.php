<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelerDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'is_primary_contact',
        'full_name',
        'email',
        'passport_number',
        'id_number',
        'date_of_birth',
        'passport_expiry',
    ];

        protected $casts = [
        'passport_number' => 'encrypted',
        'id_number' => 'encrypted',
        'date_of_birth' => 'encrypted',
        'passport_expiry' => 'encrypted',
        'is_primary_contact' => 'boolean',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
    public function travelerService(): BelongsTo
    {
        return $this->belongsTo(TravelService::class);
    }

}

