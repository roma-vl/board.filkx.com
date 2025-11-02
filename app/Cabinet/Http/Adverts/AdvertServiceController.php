<?php

namespace App\Cabinet\Http\Adverts;

use App\Http\Services\Adverts\AdvertPromotionService;
use App\Http\Services\Adverts\AdvertServiceActivator;
use App\Http\Services\Adverts\BillingService;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Boost\AdvertService;
use App\Models\Adverts\Boost\BoostPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdvertServiceController
{
    public function __construct(
        private readonly AdvertServiceActivator $advertServiceActivator,
        private readonly AdvertPromotionService $advertPromotionService,
        private readonly BillingService $billingService,
    ) {}

    public function promote(Advert $advert): Response
    {
        $advert->photo = $advert->photo()->get();

        return Inertia::render('Account/Advert/Promote', [
            'advert' => $advert,
        ]);
    }

    public function purchase(Request $request, Advert $advert): RedirectResponse
    {
        $types = $request->input('types', []);
        $couponCode = $request->input('couponCode', '');

        $order = $this->billingService->purchaseMultiple($advert, $request->user()->id, $types, $couponCode);

        foreach ($order->items as $item) {
            if (in_array($item->service_type, ['turbo7', 'turbo30', 'maximal'])) {
                $package = BoostPackage::where('name', $item->service_type)->firstOrFail();
                $this->advertPromotionService->applyPackage($advert, $item->service_type, $package);
            } else {
                $this->advertServiceActivator->activate($advert, $item->service_type, $order);
            }
        }

        return redirect()->route('account.adverts.index')
            ->with('success', 'Послуги активовано!');
    }

    public function extend(Request $request, Advert $advert): RedirectResponse
    {
        $type = $request->input('type');

        $existing = AdvertService::where('advert_id', $advert->id)
            ->where('type', $type)
            ->latest('ends_at')
            ->first();

        if (! $existing) {
            return redirect()->back()->with('error', 'Послуга не знайдена');
        }

        $durationDays = match ($type) {
            'highlight', 'urgent', 'pin', 'premium' => 7,
            'turbo7' => 7,
            'turbo30', 'maximal' => 30,
            default => 0
        };

        $order = $this->billingService->purchase($advert->id, $type);

        // Якщо активна — подовжити
        if ($existing->ends_at->isFuture()) {
            $existing->ends_at = $existing->ends_at->addDays($durationDays);
            $existing->save();
        } else {
            // Якщо вже завершилась — створити нову
            AdvertService::create([
                'advert_id' => $advert->id,
                'type' => $type,
                'starts_at' => now(),
                'ends_at' => now()->addDays($durationDays),
                'order_id' => $order->id,
            ]);
        }

        return redirect()->route('account.adverts.index')->with('success', 'Послугу продовжено!');
    }
}
