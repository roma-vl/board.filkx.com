<?php

namespace App\Registry;

use App\Models\Adverts\Advert;
use App\Models\Users\Permission;
use App\Models\Users\User;
use Illuminate\Support\Facades\Gate;

class PermissionRegistry
{
    public static function getAll(): array
    {
        return [
            ...Permission::pluck('key')->toArray(),
        ];
    }

    public static function register(): void
    {
        foreach (Permission::pluck('key') as $permission) {
            Gate::define($permission, fn (User $user) => $user->hasPermission($permission));
        }

        Gate::define('horizon', function (User $user) {
            return $user->isAdmin() || $user->isModerator();
        });

        Gate::define('manage-own-advert', function (User $user, Advert $advert) {
            return $advert->user_id === $user->id;
        });

    }

    public static function resolveUserPermissions(User $user): array
    {

        $ff = Gate::denies('manage-own-advert');

        return collect(self::getAll())
            ->filter(fn ($permission) => Gate::forUser($user)->check($permission))
            ->values()
            ->toArray();
    }
}
