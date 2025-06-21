<?php

namespace App\Http\Services\Adverts;

use App\Models\Adverts\AdvertOrder;
use Illuminate\Pagination\LengthAwarePaginator;

class AdvertOrderService
{
    public function getAdvertOrders(): LengthAwarePaginator
    {
        return AdvertOrder::with('advert')
            ->with('advert.firstPhoto')
            ->latest()
            ->paginate(20);

    }
}
