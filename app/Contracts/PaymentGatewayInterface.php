<?php

namespace App\Contracts;

use App\Models\Adverts\AdvertOrder;

interface PaymentGatewayInterface
{
    public function createPayment(AdvertOrder $order): array;

    public function handleCallback(array $data): bool;

    public function getGatewayName(): string;
}
