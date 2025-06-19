<?php

namespace App\Models\Adverts\Boost;

use App\Models\Adverts\Advert;
use App\Models\Adverts\AdvertOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertService extends Model
{
    protected $table = 'advert_services';

    protected $fillable = [
        'advert_id',
        'type',
        'starts_at',
        'ends_at',
        'auto_ups_left',
        'order_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public const TYPES = [
        'highlight',
        'pin',
        'premium',
        'urgent',
        'boost',
    ];

    public function advert(): BelongsTo
    {
        return $this->belongsTo(Advert::class);
    }

    public function isActive(): bool
    {
        return now()->between($this->starts_at, $this->ends_at);
    }

    public function scopeActive($query)
    {
        return $query->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(AdvertOrder::class, 'order_id');
    }
}
