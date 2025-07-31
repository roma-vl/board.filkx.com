<?php

namespace App\Http\Controllers\Billing;


use App\Http\Controllers\Controller;
use App\Http\Services\Payments\PaymentGatewayManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PaymentCallbackController extends Controller
{
    public function __construct(
        private readonly PaymentGatewayManager $manager
    ) {}

    public function handle(Request $request, string $gateway): Response
    {
        try {
            $service = $this->manager->driver($gateway);

            $service->handleCallback($request->all());

            return response('OK', 200);
        } catch (\Throwable $e) {
            Log::error('Payment callback error', [
                'gateway' => $gateway,
                'exception' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response('Error', 500);
        }
    }
}
