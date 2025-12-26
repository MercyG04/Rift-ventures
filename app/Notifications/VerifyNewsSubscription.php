<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;


class VerifyNewsSubscription extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // 1. Generate the Verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'newsletter.verify', // The route we define later
            now()->addMinutes(30), // Token valid for 30 minutes
            ['token' => $notifiable->unsubscribe_token] // Pass the token
        );

        return (new MailMessage)
                    ->subject('Confirm Your Subscription to Our Newsletter')
                    ->greeting('Hello ' . $notifiable->email . ',')
                    ->line('Thank you for subscribing to the newsletter! Please click the button below to confirm your email address and start receiving our travel updates.')
                    ->action('Confirm Subscription', $verificationUrl)
                    ->line('If you did not request this, you can safely ignore this email.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
