<?php

namespace App\Observers;

use App\Http\Services\Adverts\AdvertPremiumService;
use App\Models\Adverts\Boost\AdvertService;

class AdvertServiceObserver
{
    protected AdvertPremiumService $service;

    public function __construct(AdvertPremiumService $service)
    {
        $this->service = $service;
    }

    public function created(AdvertService $advertService): void
    {
        if ($advertService->type === 'premium') {
            $this->service->updatePremiumStatus($advertService->advert);
        }
    }

    public function updated(AdvertService $advertService): void
    {
        if ($advertService->type === 'premium') {
            $this->service->updatePremiumStatus($advertService->advert);
        }
    }

    public function deleted(AdvertService $advertService): void
    {
        if ($advertService->type === 'premium') {
            $this->service->updatePremiumStatus($advertService->advert);
        }
    }
}
