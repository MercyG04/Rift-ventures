<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Enums\ServiceStatus;

class TravelService extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'contact_name',
        'contact_email',
        'contact_phone',
        'service_type',
        'destination',
        'travel_date',
        'duration',
        'no_of_travellers',
        'additional_details',
        'status',
        'admin_notes',
    ];
    protected $casts = [
        'travel_date' => 'date',
        'additional_details' => 'array', 
        'status' => ServiceStatus::class, 
    ];

    // Helper to send notifications to the contact_email
    public function routeNotificationForMail($notification)
    {
        return $this->contact_email;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function travelers() {
    return $this->hasMany(TravelerDetail::class);
    }
}