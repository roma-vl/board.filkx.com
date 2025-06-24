<?php

namespace App\Models\Adverts\Boost;

use App\Models\Adverts\Advert;
use Illuminate\Database\Eloquent\Model;

class AdvertAutoLift extends Model
{
    protected $fillable = ['advert_id', 'scheduled_at'];

    public $timestamps = false;

    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }
}
