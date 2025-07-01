<?php

namespace App\Models\Newsletters;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $table = 'newsletters';

    protected $casts = [
        'body' => 'array',
        'sent' => 'boolean',
        'scheduled_at' => 'datetime',
        'html' => 'string',
    ];

    protected $fillable = [
        'title',
        'body',
        //        'body.uk',
        //        'body.en',
        'scheduled_at',
        'html',
    ];
}
