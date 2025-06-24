<?php

namespace App\Http\Controllers\Cabinet\Orders;

use App\Http\Services\PDF\PdfGeneratorInterface;
use App\Models\Adverts\AdvertOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrdersController
{
    public function __construct(private readonly PdfGeneratorInterface $pdfGenerator) {}

    public function index(Request $request): Response
    {
        $user = $request->user();
        $orders = AdvertOrder::query()
            ->where('user_id', $user->id)
            ->with('advert', 'items')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Account/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function pdfReceipt(AdvertOrder $order)
    {
        return $this->pdfGenerator->generate($order);
    }
}
