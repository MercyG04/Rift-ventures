<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NewsletterSubscription extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'email',
        'unsubscribe_token',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];
    public function routeNotificationForMail(): string
    {
        return $this->email;
    }
    
    // No relationships needed usually, as this is a standalone marketing list.
}