<?php

namespace App\Models\Adverts\Boost;

use App\Models\Adverts\Advert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertBoost extends Model
{
    protected $fillable = ['advert_id', 'type', 'starts_at', 'ends_at'];

    public function advert(): BelongsTo
    {
        return $this->belongsTo(Advert::class);
    }

    public function isActive(): bool
    {
        return now()->between($this->starts_at, $this->ends_at);
    }

    public const VIP = 'vip';

    public const HIGHLIGHT = 'highlight';

    public const PIN = 'pin';

    public const URGENT = 'urgent';

    public static function all($columns = ['*']): array
    {
        return [self::VIP, self::HIGHLIGHT, self::PIN, self::URGENT];
    }

    public static function pricing(): array
    {
        return [
            self::VIP => 25,
            self::HIGHLIGHT => 25,
            self::PIN => 15, // грн / день
            self::URGENT => 25,
        ];
    }

    public static function duration(): array
    {
        return [
            self::VIP => 1,
            self::HIGHLIGHT => 7,
            self::PIN => 1,
            self::URGENT => 7,
        ];
    }
}
