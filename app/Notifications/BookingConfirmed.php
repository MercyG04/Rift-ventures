<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use App\Models\Booking;
use App\Enums\BookingStatus;
class BookingConfirmed extends Notification
{
    use Queueable;

    protected $booking;
    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
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
       // 1. Generate the Secure, Signed URL
        // This link is only valid for 7 days and is tied to the booking reference.
        $travelerDetailsUrl = URL::temporarySignedRoute(
            'bookings.travelers.edit', // The route we define later
            now()->addDays(7), // Link valid for 7 days
            ['booking' => $this->booking->id] // Pass the booking ID
        );
        return (new MailMessage)
            ->subject(' Booking Confirmed: Action Required for Traveler Details')
            ->greeting('Congratulations!')
            ->line('Your booking for the **' . $this->booking->safariPackage->title . '** is confirmed! We are excited to have you.')
            ->line('To finalize your trip documentation, we urgently require the passport/ID details for all ' . $this->booking->num_travelers . ' travelers.')
            ->action('Submit Traveler Details', $travelerDetailsUrl)
            ->line('**Please note:** This link is secure and valid for 7 days. Completing this step quickly ensures smooth processing of your permits and visas.');
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
