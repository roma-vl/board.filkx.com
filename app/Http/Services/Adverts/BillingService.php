<?php

namespace App\Http\Services\Adverts;

use App\Models\Adverts\AdvertOrder;

class BillingService
{
    public function purchase(int $advertId, string $type): AdvertOrder
    {
        $prices = [
            'highlight' => 25,
            'urgent' => 25,
            'pin' => 15,
            'premium' => 25,
            'turbo7' => 49,
            'turbo30' => 149,
            'maximal' => 199,
        ];

        $order = AdvertOrder::create([
            'advert_id' => $advertId,
            'service_type' => $type,
            'price' => $prices[$type] ?? 0,
            'status' => 'paid', // Заглушка
            'payment_method' => 'fake_gateway',
        ]);

        return $order;
    }
}
