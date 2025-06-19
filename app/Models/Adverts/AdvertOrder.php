<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;

class AdvertOrder extends Model
{
    protected $table = 'advert_orders';

    protected $fillable = ['advert_id', 'service_type', 'price', 'status', 'payment_method'];

    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }
}
