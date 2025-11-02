<?php

namespace App\Http\Services\Adverts;

use App\Http\Services\Payments\PaymentGatewayManager;
use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;
use App\Models\Adverts\AdvertServicePrice;
use App\Models\Billing\Coupon;
use Illuminate\Support\Facades\DB;

readonly class BillingService
{
    public function __construct(
        private PaymentGatewayManager $manager,
    ) {}

    public function purchase(int $advertId, string $type, ?string $couponCode = null, string $gateway = 'liqpay'): AdvertOrder
    {
        return DB::transaction(function () use ($advertId, $type, $couponCode, $gateway) {
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
                'price' => $finalPrice,
                'status' => 'pending',
                'payment_method' => $gateway,
                'coupon_code' => $coupon?->code,
            ]);

            $paymentForm = $this->manager->driver($gateway)->pay([
                'amount' => $finalPrice,
                'description' => "Оплата послуги $type для оголошення №{$advertId}",
                'order_id' => $order->id,
            ]);

            AdvertServiceLogger::log($advertId, 'purchase', [
                'service' => $type,
                'price' => $order->price,
                'order_id' => $order->id,
            ]);

            return $order;
        });
    }

    public function purchaseMultiple(
        Advert $advert,
        int $userId,
        array $types,
        ?string $couponCode = null,
        string $gateway = 'liqpay'
    ): AdvertOrder {
        return DB::transaction(function () use ($advert, $userId, $types, $couponCode, $gateway) {
            $itemsData = [];
            $totalPrice = 0;

            foreach ($types as $type) {
                $price = $this->getPriceFor($type);
                $totalPrice += $price;
                $itemsData[] = [
                    'service_type' => $type,
                    'price' => $price,
                    'status' => 'pending',
                ];
            }

            $coupon = null;
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon) {
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

            foreach ($itemsData as $itemData) {
                $order->items()->create($itemData);
            }

            $gatewayDriver = $this->manager->driver($gateway);
            $paymentForm = $gatewayDriver->createPayment($order);

            AdvertServiceLogger::log($advert->id, 'purchase', [
                'services' => $itemsData,
                'total' => $totalPrice,
                'order_id' => $order->id,
            ]);

            return $order;
        });
    }

    public function getPriceFor(string $type): float
    {
        return AdvertServicePrice::getPriceFor($type);
    }
}
