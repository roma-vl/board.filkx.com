<?php

namespace App\Providers;

use App\Models\Users\Permission;
use App\Models\Users\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PDOException;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;

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
                $permissions = Permission::pluck('key')->toArray();

                foreach ($permissions as $permission) {
                    Gate::define($permission, function (User $user) use ($permission) {
                        return $user->hasPermission($permission);
                    });
                }
            }
        } catch (PDOException|QueryException $e) {
            // Не доступна БД — пропустити
            logger()->warning('Skipping gate registration due to DB connection issue: '.$e->getMessage());
        }

        Gate::define('telescope', function (User $user) {
            return $user->isAdmin() || $user->isModerator();
        });

        Gate::define('check-permission', function (User $user, ?array $permissions = null) {
            $authUser = auth()->user() ?? $user;

            if ($permissions && method_exists(User::class, 'permissionExists') && User::permissionExists($authUser, $permissions)) {
                return Response::allow();
            }

            return Response::deny(__('errors.forbidden'), ResponseStatus::HTTP_FORBIDDEN);
        });
    }
}
