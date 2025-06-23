<?php

namespace App\Http\Controllers\Cabinet\Orders;

use App\Models\Adverts\AdvertOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrdersController
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $orders = AdvertOrder::query()
            ->where('user_id', $user->id)
            ->with('advert', 'items')
            ->paginate(20);

        return Inertia::render('Account/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function pdfReceipt(AdvertOrder $order)
    {
        $order->load('items'); // ← важливо

        $pdf = Pdf::loadView('pdf.receipt', ['order' => $order]);

        return $pdf->stream("receipt_{$order->id}.pdf");
    }
}
