<?php

namespace App\Services\Adverts;

use App\Enum\AdvertServiceType;
use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;

interface AdvertServiceHandlerInterface
{
    public function handle(Advert $advert, AdvertServiceType $type, AdvertOrder $order): void;

    public function supports(AdvertServiceType $type): bool;
}
