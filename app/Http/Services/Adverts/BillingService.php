<?php

namespace App\Http\Services\Adverts;

use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;
use App\Models\AdvertServicePrice;
use App\Models\Coupon;

class BillingService
{
    public function purchase(int $advertId, string $type, ?string $couponCode = null): AdvertOrder
    {
        $basePrice = $this->getPriceFor($type);

        $finalPrice = $basePrice;

        $coupon = null;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValidFor($type)) {
                $finalPrice = $coupon->applyTo($basePrice);
                $coupon->increment('used_count');
            }
        }

        $order = AdvertOrder::create([
            'advert_id' => $advertId,
            'service_type' => $type,
            'price' => $finalPrice,
            'status' => 'paid',
            'payment_method' => 'fake_gateway',
            'coupon_code' => $coupon?->code,
        ]);

        AdvertServiceLogger::log($advertId, 'purchase', [
            'service' => $type,
            'price' => $order->price,
            'order_id' => $order->id,
        ]);

        return $order;
    }

    public function purchaseMultiple(Advert $advert, int $userId, array $types, ?string $couponCode = null): AdvertOrder
    {
        $totalPrice = 0;
        $items = [];

        foreach ($types as $type) {
            $price = $this->getPriceFor($type);
            $totalPrice += $price;
            $items[] = [
                'service_type' => $type,
                'price' => $price,
            ];
        }

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValidFor($type)) {
                $totalPrice = $coupon->applyTo($totalPrice);
                $coupon->increment('used_count');
            }
        }

        $order = AdvertOrder::create([
            'advert_id' => $advert->id,
            'user_id' => $userId,
            'price' => $totalPrice,
            'status' => 'paid',
            'payment_method' => 'fake_gateway',
            'coupon_code' => $couponCode,
        ]);

        $advert->update(['premium' => 1]);

        foreach ($items as $item) {
            $order->items()->create($item);
        }

        AdvertServiceLogger::log($advert->id, 'purchase', [
            'services' => $items,
            'total' => $totalPrice,
            'order_id' => $order->id,
        ]);

        return $order;
    }

    public function getPriceFor(string $type): float
    {
        return AdvertServicePrice::getPriceFor($type);
    }
}
