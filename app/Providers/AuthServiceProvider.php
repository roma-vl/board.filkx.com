<?php

namespace App\Providers;

use App\Models\Adverts\Advert;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PDOException;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            DB::connection()->getPdo();

            if (Schema::hasTable('permissions')) {
                $permissions = \App\Models\Permission::pluck('key')->toArray();

                foreach ($permissions as $permission) {
                    Gate::define($permission, function (User $user) use ($permission) {
                        return $user->hasPermission($permission);
                    });
                }
            }
        } catch (PDOException | QueryException $e) {
            // Не доступна БД — пропустити
            logger()->warning('Skipping gate registration due to DB connection issue: ' . $e->getMessage());
        }

        Gate::define('telescope', function (User $user) {
            return $user->isAdmin() || $user->isModerator();
        });

        Gate::define('manage.own.advert', function (User $user, Advert $advert) {
            return $advert->user_id === $user->id;
        });
    }
}
