<?php

namespace App\Models\Adverts\Boost;

use Illuminate\Database\Eloquent\Model;

class BoostPackage extends Model
{
    protected $casts = [
        'features' => 'array',
        'social_share' => 'boolean',
    ];

    protected $table = 'advert_boost_packages';

    protected $fillable = ['name', 'features', 'duration_days', 'price', 'social_share', 'auto_lift_count'];
}
