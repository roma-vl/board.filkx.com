<?php

namespace App\Http\Services\Adverts;

use App\Models\Adverts\AdvertServiceLog;

class AdvertServiceLogger
{
    public static function log(int $advertId, string $type, array $payload = []): void
    {
        AdvertServiceLog::create([
            'advert_id' => $advertId,
            'type' => $type,
            'payload' => $payload,
        ]);
    }
}
