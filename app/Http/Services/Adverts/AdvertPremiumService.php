<?php

namespace App\Http\Services\Adverts;

use App\Models\Adverts\Advert;

class AdvertPremiumService
{
    public function updatePremiumStatus(Advert $advert): void
    {
        $hasPremium = $advert->services()
            ->where('type', 'premium')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->exists();

        $advert->update(['premium' => $hasPremium ? 1 : 0]);
    }
}
