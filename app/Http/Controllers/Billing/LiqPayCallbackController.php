<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Payments\LiqPayService;

class LiqPayCallbackController extends Controller
{
    public function handle(Request $request, LiqPayService $liqpayService)
    {
        $data = $request->input('data');
        $signature = $request->input('signature');

        try {
            $liqpayService->handleCallback([
                'data' => $data,
                'signature' => $signature
            ]);
        } catch (\Exception $e) {
            \Log::error('LiqPay callback error: '.$e->getMessage());
            return response('Error', 400);
        }

        return response('OK', 200);
    }
}
