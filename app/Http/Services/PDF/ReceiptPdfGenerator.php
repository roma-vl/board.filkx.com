<?php

namespace App\Http\Services\PDF;

use App\Models\Adverts\AdvertOrder;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptPdfGenerator implements PdfGeneratorInterface
{
    public function generate(AdvertOrder $order)
    {
        $order->load('items');

        $pdf = Pdf::loadView('pdf.receipt', ['order' => $order]);

        return $pdf->stream("order_{$order->id}.pdf");
    }
}
