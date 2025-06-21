<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSocial extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'avatar_url',
    ];

    protected $casts = [];

    protected $table = 'users_socials';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
