<?php

namespace App\Http\Services\PDF;

use App\Models\Adverts\AdvertOrder;

interface PdfGeneratorInterface
{
    public function generate(AdvertOrder $order);
}
