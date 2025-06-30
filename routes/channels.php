<?php

use Illuminate\Support\Facades\Broadcast;

// if (Config::get('broadcasting.default') !== 'pusher') {
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('test.name', function ($user) {
    \Log::info('Authorizing user ID: '.$user->id);

    return $user;
});

// }
