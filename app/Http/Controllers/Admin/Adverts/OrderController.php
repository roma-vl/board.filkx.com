<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Admin\Controller;
use App\Http\Services\Adverts\AdvertOrderService;
use App\Http\Services\PDF\PdfGeneratorInterface;
use App\Models\Adverts\AdvertOrder;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(
        private readonly PdfGeneratorInterface $pdfGenerator,
        private readonly AdvertOrderService $advertOrderService
    ) {}

    public function orders(): Response
    {
        return Inertia::render('Admin/Orders/Orders', [
            'orders' => $this->advertOrderService->getAdvertOrders(),
        ]);
    }

    public function pdfReceipt(AdvertOrder $order)
    {
        return $this->pdfGenerator->generate($order);
    }
}
