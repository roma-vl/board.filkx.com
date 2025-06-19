<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Admin\Controller;
use App\Http\Services\Adverts\AdvertOrderService;
use Inertia\Inertia;

class OrderController extends Controller
{
    private AdvertOrderService $advertOrderService;

    public function __construct(AdvertOrderService $advertOrderService)
    {

        $this->advertOrderService = $advertOrderService;
    }

    public function orders()
    {
        return Inertia::render('Admin/Orders/Orders', [
            'orders' => $this->advertOrderService->getAdvertOrders(),
        ]);
    }
}
