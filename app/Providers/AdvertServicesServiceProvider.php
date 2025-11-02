<?php

namespace App\Providers;

use App\Services\Adverts\PromotionServiceHandler;
use App\Services\Adverts\ServiceDispatcher;
use App\Services\Adverts\StandardServiceHandler;
use Illuminate\Support\ServiceProvider;

class AdvertServicesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ServiceDispatcher::class, function ($app) {
            $handlers = [
                $app->make(PromotionServiceHandler::class),
                $app->make(StandardServiceHandler::class),
            ];

            return new ServiceDispatcher($handlers);
        });
    }
}
