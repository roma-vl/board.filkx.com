<?php

namespace App\Http\Services\Adverts;

use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;
use App\Models\Adverts\Boost\AdvertService;

class AdvertServiceActivator
{
    public function activate(Advert $advert, string $type, AdvertOrder $order): void
    {
        $durationDays = match ($type) {
            'highlight', 'urgent', 'pin', 'premium' => 7,
            'turbo7' => 7,
            'turbo30', 'maximal' => 30,
            default => 0
        };

        AdvertService::create([
            'advert_id' => $advert->id,
            'type' => $type,
            'starts_at' => now(),
            'ends_at' => now()->addDays($durationDays),
            'auto_ups_left' => $type === 'maximal' ? 30 : null,
            'order_id' => $order->id,
        ]);

        AdvertServiceLogger::log($advert->id, 'activate', [
            'service' => $type,
            'duration_days' => $durationDays,
        ]);
    }
}
