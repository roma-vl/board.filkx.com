<?php

namespace App\Models;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
