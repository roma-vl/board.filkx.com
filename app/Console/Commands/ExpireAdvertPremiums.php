<?php

namespace App\Console\Commands;

namespace App\Console\Commands;

use App\Http\Services\Adverts\AdvertPremiumService;
use App\Models\Adverts\Advert;
use App\Notifications\Advert\PremiumExpired;
use Illuminate\Console\Command;

class ExpireAdvertPremiums extends Command
{
    protected $signature = 'adverts:expire-premium';

    protected $description = 'Remove premium flag from adverts with expired premium service';

    public function handle(AdvertPremiumService $service): void
    {
        Advert::where('premium', true)
            ->whereDoesntHave('services', function ($q) {
                $q->where('type', 'premium')
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>=', now());
            })
            ->with('user') // важливо для notify
            ->each(function (Advert $advert) use ($service) {
                $service->updatePremiumStatus($advert);

                if ($advert->user) {
                    $advert->user->notify(new PremiumExpired($advert));
                }

                $this->info("Unset premium and notified user for advert #{$advert->id}");
            });
    }
}
