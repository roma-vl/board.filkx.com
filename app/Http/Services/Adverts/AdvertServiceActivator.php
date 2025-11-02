<?php

namespace App\Http\Services\Adverts;

use App\Enum\AdvertServiceType;
use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;
use App\Models\Adverts\Boost\AdvertService;

class AdvertServiceActivator
{
    public function activate(Advert $advert, string|AdvertServiceType $type, AdvertOrder $order): void
    {
        $type = $type instanceof AdvertServiceType ? $type : AdvertServiceType::fromString($type);

        AdvertService::create([
            'advert_id' => $advert->id,
            'type' => $type->value,
            'starts_at' => now(),
            'ends_at' => now()->addDays($type->durationDays()),
            'auto_ups_left' => $type === AdvertServiceType::MAXIMAL ? 30 : null,
            'order_id' => $order->id,
        ]);

        $this->updateAdvertFields($advert, $type);

        AdvertServiceLogger::log($advert->id, 'activate', [
            'service' => $type->value,
            'duration_days' => $type->durationDays(),
        ]);
    }

    private function updateAdvertFields(Advert $advert, AdvertServiceType $type): void
    {
        $fieldUpdates = match ($type) {
            AdvertServiceType::HIGHLIGHT => ['highlight' => 1],
            AdvertServiceType::URGENT => ['urgent' => 1],
            AdvertServiceType::PIN => ['pin' => 1],
            AdvertServiceType::PREMIUM => ['premium' => 1],
            default => []
        };

        if (! empty($fieldUpdates)) {
            $advert->update($fieldUpdates);
        }
    }
}
