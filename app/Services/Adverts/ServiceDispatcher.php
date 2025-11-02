<?php

namespace App\Services\Adverts;

use App\Enum\AdvertServiceType;
use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;

readonly class ServiceDispatcher
{
    public function __construct(
        private iterable $handlers
    ) {}

    public function dispatch(Advert $advert, AdvertServiceType $type, AdvertOrder $order): void
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($type)) {
                $handler->handle($advert, $type, $order);

                return;
            }
        }

        throw new \InvalidArgumentException("No handler found for service type: {$type->value}");
    }
}
