<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertOrderItem extends Model
{
    protected $fillable = ['order_id', 'service_type', 'price'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(AdvertOrder::class, 'order_id');
    }
}
