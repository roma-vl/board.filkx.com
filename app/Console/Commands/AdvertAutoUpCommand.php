<?php

namespace App\Console\Commands;

use App\Http\Services\Adverts\AdvertService;
use Illuminate\Console\Command;

class AdvertAutoUpCommand extends Command
{
    protected $signature = 'adverts:auto-up';

    protected $description = 'Підіймає оголошення з активним автопідняттям';

    public function handle(): int
    {
        AdvertService::query()
            ->where('type', 'boost')
            ->where('auto_ups_left', '>', 0)
            ->where('ends_at', '>=', now())
            ->each(function ($service) {
                $advert = $service->advert;

                $advert->updated_at = now();
                $advert->save();

                $service->decrement('auto_ups_left');
                $this->info("🔼 Оголошення #{$advert->id} піднято.");
            });

        return self::SUCCESS;
    }
}
