<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;

class AdvertStatus extends Model
{
    protected $table = 'advert_statuses';

    protected $fillable = [
        'name',
        'color',
        'state',
    ];

    protected $casts = [
        'state' => 'integer',
        'name' => 'array',
    ];

    public function adverts()
    {
        return $this->hasMany(Advert::class, 'status_id', 'state');
    }
}
