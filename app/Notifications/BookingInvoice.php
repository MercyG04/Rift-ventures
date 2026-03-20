<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use app\Models\Booking;
class BookingInvoice extends Notification
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
        return (new MailMessage)
            ->subject('Booking Received: Invoice #' . $this->booking->id)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Thank you for booking the **' . $this->booking->safariPackage->title . '**!')
            ->line('Your booking reference is: #' . $this->booking->id)
            ->line('**Selected Option:** ' . $this->booking->package_variant_name)
            ->line('**Total Amount Due:** KES ' . number_format($this->booking->total_price / 100))
            ->line('---')
            ->line('**Payment Instructions:**')
            ->line('Please make your payment via:')
            ->line('1. **M-Pesa Till:** 123456 (Rift Ventures Safaris)')
            ->line('2. **Bank Transfer:** KCB Account 1234567890')
            ->line('Please use your Booking Reference (#' . $this->booking->id . ') as the payment description.')
            ->line('---')
            ->line('Once we receive your payment, we will confirm your booking and send you a link to provide traveler details.')
            ->action('View My Booking', route('bookings.show', $this->booking));
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
