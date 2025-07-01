<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;

class AdvertServiceLog extends Model
{
    protected $table = 'advert_service_logs';

    protected $fillable = ['advert_id', 'type', 'payload'];

    protected $casts = [
        'payload' => 'array',
    ];
}
