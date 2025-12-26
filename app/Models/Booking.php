<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'safari_package_id',
        'package_variant_name', 
        'booking_date',
        'contact_name',
        'contact_email',
        'contact_phone',
        'num_travelers',
        'total_price', 
        'status',     
        'special_requests',
    ];

    protected $casts = [
        'status' => BookingStatus::class,
        'booking_date' => 'date', 
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function safariPackage(): BelongsTo
    {
        return $this->belongsTo(SafariPackage::class);
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function travelerDetails(): HasMany
    {
        return $this->hasMany(TravelerDetail::class);
    }
    public function primaryContact(): HasOne
    {
        return $this->hasOne(TravelerDetail::class)->where('is_primary_contact', true);
    }
}
