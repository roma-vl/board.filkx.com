<?php

namespace App\Notifications\Advert;


use App\Models\Adverts\Advert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PremiumExpired extends Notification
{
    use Queueable;

    public function __construct(public readonly Advert $advert) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Преміум послуга завершилась')
            ->greeting("Привіт, {$notifiable->name}!")
            ->line("Преміум розміщення для вашого оголошення '{$this->advert->title}' завершилось.")
            ->action('Продовжити преміум', route('purchase.advert', $this->advert->id))
            ->line('Дякуємо, що користуєтесь нашим сервісом!');
    }
}
