<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ChatNotification extends Notification
{
    use Queueable;

    public function __construct(
        public array $data = []
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return $this->data;
    }
}
