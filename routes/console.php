<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('advert:expire')->hourly()
    ->appendOutputTo(storage_path('logs/cron-advert-expire.log'));
Schedule::command('banner:expire')->hourly()
    ->appendOutputTo(storage_path('logs/cron-banner-expire.log'));
Schedule::command('adverts:expire-premium')->hourly()
    ->appendOutputTo(storage_path('logs/cron-advert-expire-premium.log'));
Schedule::command('adverts:check-expiring-services')->dailyAt('09:00');
Schedule::command('newsletters:send')->everyFiveMinutes()
    ->appendOutputTo(storage_path('logs/newsletters.log'));
