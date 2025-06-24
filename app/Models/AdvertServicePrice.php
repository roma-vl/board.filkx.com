<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertServicePrice extends Model
{
    protected $table = 'advert_service_prices';

    protected $fillable = ['service_type', 'price', 'currency'];

    public static function getPriceFor(string $type): float
    {
        return self::where('service_type', $type)->value('price') ?? 0;
    }
}
