<?php

namespace App\Notifications;

use App\Models\Adverts\Boost\AdvertService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceExpiringSoon extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public AdvertService $service) {}

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
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Ваша послуга скоро завершиться')
            ->line("Послуга {$this->service->type} для оголошення завершується {$this->service->ends_at->format('d.m.Y')}")
            ->action('Продовжити послугу', route('account.adverts.promote', $this->service->advert_id));
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
