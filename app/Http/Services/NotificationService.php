<?php

namespace App\Http\Services;

use App\Models\User;
use App\Notifications\ChatNotification;

class NotificationService
{
    public static function notify(User $user, string $type, array $data)
    {
        if ($type === 'chat.new') {
            $user->notify(new ChatNotification($data));
        }

        // Можна реалізувати типи через enum + стратегії
    }
}
