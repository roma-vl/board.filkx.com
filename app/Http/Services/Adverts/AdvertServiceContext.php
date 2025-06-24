<?php

namespace App\Http\Services\Adverts;

use App\Http\Services\Adverts\ServiceStrategies\ServiceStrategyInterface;
use App\Models\Adverts\Advert;
use InvalidArgumentException;

class AdvertServiceContext
{
    private array $strategies = [];

    public function register(string $type, ServiceStrategyInterface $strategy): void
    {
        $this->strategies[$type] = $strategy;
    }

    public function apply(string $type, Advert $advert): void
    {
        if (! isset($this->strategies[$type])) {
            throw new InvalidArgumentException("Unknown service type: $type");
        }

        $this->strategies[$type]->apply($advert);
    }
}
