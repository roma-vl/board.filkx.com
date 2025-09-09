<?php

namespace App\Http\Services\Payments;

use App\Contracts\PaymentGatewayInterface;

class PaymentGatewayManager
{
    public function driver(string $driver): PaymentGatewayInterface
    {
        return match ($driver) {
            'liqpay' => new LiqPayService,
            // 'stripe' => new StripeService(), // для майбутнього
            default => throw new \InvalidArgumentException("Unsupported payment gateway [$driver]"),
        };
    }
}
