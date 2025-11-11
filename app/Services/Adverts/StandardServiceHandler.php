<?php

namespace App\Services\Adverts;

use App\Enum\AdvertServiceType;
use App\Http\Services\Adverts\AdvertServiceActivator;
use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;

readonly class StandardServiceHandler implements AdvertServiceHandlerInterface
{
    public function __construct(
        private AdvertServiceActivator $serviceActivator
    ) {}

    public function supports(AdvertServiceType $type): bool
    {
        return in_array($type, [
            AdvertServiceType::HIGHLIGHT,
            AdvertServiceType::URGENT,
            AdvertServiceType::PIN,
            AdvertServiceType::PREMIUM,
        ]);
    }

    public function handle(Advert $advert, AdvertServiceType $type, AdvertOrder $order): void
    {
        $this->serviceActivator->activate($advert, $type, $order);
    }
}
