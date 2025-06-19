<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Services\Adverts\AdvertPromotionService;
use App\Http\Services\Adverts\AdvertServiceActivator;
use App\Http\Services\Adverts\BillingService;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Boost\BoostPackage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdvertServiceController
{
    private AdvertServiceActivator $advertServiceActivator;

    private AdvertPromotionService $advertPromotionService;

    private BillingService $billingService;

    public function __construct(
        AdvertServiceActivator $advertServiceActivator,
        AdvertPromotionService $advertPromotionService,
        BillingService $billingService,
    ) {
        $this->advertServiceActivator = $advertServiceActivator;
        $this->advertPromotionService = $advertPromotionService;
        $this->billingService = $billingService;
    }

    public function promote(Advert $advert)
    {
        $advert->photo = $advert->photo()->get();

        return Inertia::render('Account/Advert/Promote', [
            'advert' => $advert,
        ]);
    }

    public function purchase(Request $request, Advert $advert)
    {
        $types = $request->input('types', []);

        foreach ($types as $type) {
            if (in_array($type, ['turbo7', 'turbo30', 'maximal'])) {
                $package = BoostPackage::where('name', $type)->firstOrFail();
                $this->billingService->purchase($advert->id, $type);
                $this->advertPromotionService->applyPackage($advert, $type, $package);
            } else {
                $this->advertServiceActivator->activate($advert->id, $type);
            }
        }

        return redirect()->route('account.adverts.index')
            ->with('success', 'Послуги активовано!');
    }
}
