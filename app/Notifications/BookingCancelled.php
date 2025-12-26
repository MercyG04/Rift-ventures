<?php 

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Booking;

class BookingCancelled extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(' Booking Cancellation Confirmation of ' . $this->booking->safariPackage->title)
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('This email confirms the cancellation of your booking (Ref: #' . $this->booking->id . ') for the ' . $this->booking->safariPackage->title . '.')
            ->line('---')
            ->line('**Refund Policy Summary:**')
            ->line('* Refunds are processed according to our Terms & Conditions based on the remaining days until the departure date (' . $this->booking->booking_date->format('Y-m-d') . ').')
            ->line('* We estimate the refund process will take **5-10 business days** once the amount is finalized.')
            ->line('---')
            ->line('If you did not initiate this cancellation, please contact us immediately.')
            ->salutation('Best regards, The Safari Team');
    }
}