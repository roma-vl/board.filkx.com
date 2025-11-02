<?php

namespace App\Jobs\Advert;

use App\Enum\AdvertServiceType;
use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;
use App\Services\Adverts\ServiceDispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActivateAdvertServicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public int $timeout = 120;

    public function __construct(
        public Advert $advert,
        public AdvertOrder $order,
    ) {}

    public function handle(ServiceDispatcher $dispatcher): void
    {
        foreach ($this->order->items as $item) {
            try {
                $serviceType = AdvertServiceType::fromString($item->service_type);
                $dispatcher->dispatch($this->advert, $serviceType, $this->order);

                Log::info('Advert service activated', [
                    'advert_id' => $this->advert->id,
                    'service_type' => $serviceType->value,
                    'order_id' => $this->order->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to activate advert service', [
                    'advert_id' => $this->advert->id,
                    'service_type' => $item->service_type ?? 'unknown',
                    'order_id' => $this->order->id,
                    'error' => $e->getMessage(),
                ]);

                continue;
            }
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::error('ActivateAdvertServicesJob failed', [
            'advert_id' => $this->advert->id,
            'order_id' => $this->order->id,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
