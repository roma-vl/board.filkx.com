<?php

namespace App\Services\Adverts;

use App\Enum\AdvertServiceType;
use App\Http\Services\Adverts\AdvertPromotionService;
use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;
use App\Models\Adverts\Boost\BoostPackage;

readonly class PromotionServiceHandler implements AdvertServiceHandlerInterface
{
    public function __construct(
        private AdvertPromotionService $promotionService
    ) {}

    public function supports(AdvertServiceType $type): bool
    {
        return in_array($type, [
            AdvertServiceType::TURBO7,
            AdvertServiceType::TURBO30,
            AdvertServiceType::MAXIMAL,
        ]);
    }

    public function handle(Advert $advert, AdvertServiceType $type, AdvertOrder $order): void
    {
        $package = BoostPackage::where('name', $type->value)->firstOrFail();
        $this->promotionService->applyPackage($advert, $type->value, $package);
    }
}
