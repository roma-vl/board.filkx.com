<?php

namespace App\Http\Services\Adverts\ServiceStrategies;

use App\Http\Services\Adverts\BillingService;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Boost\AdvertService;

class HighlightService implements ServiceStrategyInterface
{
    public function __construct(private readonly BillingService $billingService) {}

    public function apply(Advert $advert): void
    {
        $order = $this->billingService->purchase($advert->id, 'highlight');

        AdvertService::create([
            'advert_id' => $advert->id,
            'type' => 'highlight',
            'starts_at' => now(),
            'ends_at' => now()->addDays(7),
            'order_id' => $order->id,
        ]);
    }
}
