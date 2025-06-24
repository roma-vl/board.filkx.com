<?php

namespace App\Http\Services\Adverts\ServiceStrategies;

use App\Models\Adverts\Advert;

interface ServiceStrategyInterface
{
    public function apply(Advert $advert): void;
}
