<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'discount_type',
        'discount_amount',
        'applicable_services',
        'usage_limit',
        'used_count',
        'expires_at',
    ];

    protected $casts = [
        'applicable_services' => 'array',
        'expires_at' => 'datetime',
    ];

    public function isValidFor(string $service): bool
    {
        return
            (! $this->expires_at || now()->lt($this->expires_at)) &&
            ($this->usage_limit === null || $this->used_count < $this->usage_limit) &&
            (empty($this->applicable_services) || in_array($service, $this->applicable_services));
    }

    public function applyTo(float $price): float
    {
        return $this->discount_type === 'percent'
            ? round($price * (1 - $this->discount_amount / 100), 2)
            : max(0, $price - $this->discount_amount);
    }
}
