<?php

namespace App\Http\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Adverts\AdvertOrder;
use LiqPay;

class LiqPayService implements PaymentGatewayInterface
{
    protected LiqPay $client;

    public function __construct()
    {
        $this->client = new LiqPay(config('liqpay.public_key'), config('liqpay.private_key'));
    }

    public function createPayment(AdvertOrder $order): array
    {
        $params = [
            'version' => '3',
            'action' => 'pay',
            'amount' => $order->price,
            'currency' => 'UAH',
            'description' => 'Оплата послуг оголошення',
            'order_id' => $order->id,
            'server_url' => route('liqpay.callback'),
            'result_url' => route('account.adverts.index'),
        ];

        return [
            'form' => $this->client->cnb_form($params) // тут повертається HTML-форма для платежу
        ];
    }

//    public function handleCallback(array $data): bool
//    {
//        // перевірка підпису та статусу
//        // логіка зміни статусу ордера
//        return true;
//    }
    public function handleCallback(array $data): bool
    {
        $liqpayData = $data['data'] ?? '';
        $signature = $data['signature'] ?? '';

        // 1. Перевірка підпису
        $expectedSignature = base64_encode(
            sha1(env('LIQPAY_PRIVATE_KEY') . $liqpayData . env('LIQPAY_PRIVATE_KEY'), true)
        );

        if ($signature !== $expectedSignature) {
            throw new \RuntimeException('Invalid LiqPay signature');
        }

        // 2. Декодування JSON
        $decoded = json_decode(base64_decode($liqpayData), true);

        if (!isset($decoded['order_id']) || !isset($decoded['status'])) {
            throw new \RuntimeException('Invalid LiqPay data payload');
        }

        // 3. Пошук замовлення
        $order = AdvertOrder::findOrFail($decoded['order_id']);

        // 4. Оновлення статусу
        if (in_array($decoded['status'], ['success', 'sandbox'])) {
            $order->update([
                'status' => 'paid',
                'payment_method' => 'liqpay',
            ]);

            // тут можна активувати послугу або викликати serviceActivator
        } elseif ($decoded['status'] === 'failure') {
            $order->update([
                'status' => 'failed',
            ]);
        }
    }


    public function getGatewayName(): string
    {
        return 'liqpay';
    }
}
