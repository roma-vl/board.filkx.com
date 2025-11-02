<?php

namespace App\Cabinet\Http\Adverts;

use App\Enum\AdvertServiceType;
use App\Http\Services\Adverts\BillingService;
use App\Jobs\Advert\ActivateAdvertServicesJob;
use App\Models\Adverts\Advert;
use App\Models\Adverts\Boost\AdvertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

readonly class AdvertServiceController
{
    public function __construct(
        private BillingService $billingService,
    ) {}

    public function promote(Advert $advert): Response
    {
        $advert->load('photo');

        return Inertia::render('Account/Advert/Promote', [
            'advert' => $advert,
        ]);
    }

    public function purchase(Request $request, Advert $advert): RedirectResponse
    {
        $types = $request->input('types', []);
        $couponCode = $request->input('couponCode', '');

        $order = DB::transaction(function () use ($advert, $request, $types, $couponCode) {
            $order = $this->billingService->purchaseMultiple(
                $advert,
                $request->user()->id,
                $types,
                $couponCode
            );

            DB::afterCommit(function () use ($advert, $order) {
                ActivateAdvertServicesJob::dispatch($advert, $order);
            });

            return $order;
        });

        return redirect()->route('account.adverts.index')
            ->with('success', 'Послуги активовано!');
    }

    public function extend(Request $request, Advert $advert): RedirectResponse
    {
        $type = $request->input('type');
        $existing = AdvertService::where('advert_id', $advert->id)
            ->where('type', $type)
            ->latest('ends_at')
            ->lockForUpdate()
            ->first();

        if (! $existing) {
            return redirect()->back()->with('error', 'Послуга не знайдена');
        }

        $serviceType = AdvertServiceType::fromString($type);
        $order = $this->billingService->purchase($advert->id, $type);

        $this->extendService($existing, $serviceType, $order);

        return redirect()->route('account.adverts.index')
            ->with('success', 'Послугу продовжено!');
    }

    private function extendService(AdvertService $existing, AdvertServiceType $type, $order): void
    {
        if ($existing->ends_at->isFuture()) {
            $existing->update([
                'ends_at' => $existing->ends_at->addDays($type->durationDays()),
            ]);
        } else {
            $existing->replicate()->fill([
                'starts_at' => now(),
                'ends_at' => now()->addDays($type->durationDays()),
                'order_id' => $order->id,
            ])->save();
        }
    }
}
