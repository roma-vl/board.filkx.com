<?php

namespace App\Http\Services\Adverts;

use App\Http\Services\Payments\PaymentGatewayManager;
use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;
use App\Models\Adverts\AdvertServicePrice;
use App\Models\Billing\Coupon;

class BillingService
{
    public function __construct(
        private readonly PaymentGatewayManager $manager,
    ) {}

    public function purchase(int $advertId, string $type, ?string $couponCode = null, string $gateway = 'liqpay'): AdvertOrder
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
//            'service_type' => $type,
            'price' => $finalPrice,
            'status' => 'pending',
            'payment_method' => 'fake_gateway',
            'coupon_code' => $coupon?->code,
        ]);

        $form = $this->manager->driver($gateway)->pay([
            'amount' => $finalPrice,
            'description' => "Оплата послуги $type для оголошення №{$advertId}",
            'order_id' => $order->id,
        ]);

        AdvertServiceLogger::log($advertId, 'purchase', [
            'service' => $type,
            'price' => $order->price,
            'order_id' => $order->id,
        ]);

        return $form;
    }

    public function purchaseMultiple(Advert $advert, int $userId, array $types, ?string $couponCode = null, $gateway = 'liqpay'): AdvertOrder
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
            'status' => 'pending',
            'payment_method' => $gateway,
            'coupon_code' => $couponCode,
        ]);

        $form = $this->manager->driver($gateway)->pay([
            'amount' => $totalPrice,
            'description' => "Оплата послуги $type для оголошення №{$advert->id}",
            'order_id' => $order->id,
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
