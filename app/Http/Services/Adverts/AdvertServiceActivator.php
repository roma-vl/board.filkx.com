<?php

namespace App\Http\Services\Adverts;

use App\Models\Adverts\Boost\AdvertService;
use Barryvdh\DomPDF\Facade\Pdf;

class AdvertServiceActivator
{
    private BillingService $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function activate(int $advertId, string $type)
    {
        $durationDays = match ($type) {
            'highlight', 'urgent', 'pin', 'premium' => 7,
            'turbo7' => 7,
            'turbo30', 'maximal' => 30,
            default => 0
        };

        $order = $this->billingService->purchase($advertId, $type);

        AdvertService::create([
            'advert_id' => $advertId,
            'type' => $type,
            'starts_at' => now(),
            'ends_at' => now()->addDays($durationDays),
            'auto_ups_left' => $type === 'maximal' ? 30 : null,
            'order_id' => $order->id,
        ]);

        return Pdf::loadView('pdf.receipt', ['order' => $order])
            ->download("receipt_{$order->id}.pdf");
    }
}
