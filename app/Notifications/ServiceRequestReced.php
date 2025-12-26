<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TravelService;
class ServiceRequestReced extends Notification
{
    use Queueable;
    protected $service;

    /**
     * Create a new notification instance.
     */
    public function __construct(TravelService $service)
    {
        $this->service = $service;
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
        $serviceName = ucfirst(str_replace('_', ' ', $this->service->service_type));

        return (new MailMessage)
            ->subject( $serviceName . ' Request Received')
            ->greeting('Hello ' . $this->service->contact_name . ',')
            ->line('Thank you for contacting Rift Ventures Safaris.')
            ->line('We have received your request for **' . $serviceName . '** to **' . ($this->service->destination ?? 'your destination') . '**.')
            ->line('One of our travel agents is reviewing your details and will contact you via phone or email shortly to provide a quote or guide you through the next steps.')
            ->line('Reference Number: #REQ-' . $this->service->id)
            ->salutation('Warm Regards, Rift Ventures Safaris Team');
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
