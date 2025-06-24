<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdvertOrder extends Model
{
    protected $table = 'advert_orders';

    protected $fillable = ['advert_id', 'user_id', 'service_type', 'price', 'status', 'payment_method'];

    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(AdvertOrderItem::class, 'order_id');
    }
}
