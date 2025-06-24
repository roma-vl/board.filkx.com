<?php

namespace App\Console\Commands;

use App\Models\Adverts\Boost\AdvertService;
use App\Notifications\ServiceExpiringSoon;
use Illuminate\Console\Command;

class CheckExpiringAdvertServices extends Command
{
    protected $signature = 'adverts:check-expiring-services';

    protected $description = 'Check advert services that are about to expire and notify users.';

    public function handle()
    {
        $services = AdvertService::where('notified_before_expire', '0')
            ->whereDate('ends_at', now()->addDays(2)->toDateString())
            ->with('advert.user')
            ->get();

        foreach ($services as $service) {
            if ($service->advert->user) {
                $service->advert->user->notify(new ServiceExpiringSoon($service));
                $service->update([
                    'notified_before_expire' => '1',
                ]);

                $this->info("Notified user #{$service->advert->user_id} for service {$service->type}");
            }
        }
    }
}
