<?php

namespace App\Http\Services\Adverts;

use App\Models\Adverts\Advert;
use App\Models\Adverts\Boost\AdvertBoost;
use App\Models\Adverts\Boost\BoostPackage;

class AdvertPromotionService
{
    public function applyPackage(Advert $advert, string $type, BoostPackage $package): void
    {
        $this->applyBoost($advert, $type, $package->duration_days);

        if ($package->auto_lift_count > 0) {
            for ($i = 0; $i < $package->auto_lift_count; $i++) {
                $advert->autoLifts()->create([
                    'scheduled_at' => now()->addDays($i),
                ]);
            }
        }

        if ($package->social_share) {
            // Тут виклик на соціальні мережі
        }
    }

    public function applyBoost(Advert $advert, string $type, int $days): AdvertBoost
    {
        return AdvertBoost::create([
            'advert_id' => $advert->id,
            'type' => $type,
            'starts_at' => now(),
            'ends_at' => now()->addDays($days),
        ]);
    }
}
