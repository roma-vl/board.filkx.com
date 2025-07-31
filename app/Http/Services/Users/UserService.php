<?php

namespace App\Http\Services\Users;

use App\Filters\UserFilter;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Users\User;
use App\Models\Users\UserSocial;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Laravel\Socialite\Facades\Socialite;

class UserService
{
    const array DEFAULT_ROLE_TO_USER = [3]; // role -> user

    public function createUser(RegisterRequest $request): User
    {
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => bcrypt($request->password),
            'email_verified_at' => now(),
        ]);

        $user->roles()->sync(self::DEFAULT_ROLE_TO_USER);

        return $user;
    }

    public function createUserFromAdmin(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => bcrypt($data['password']),
            'email_verified_at' => now(),
        ]);

        if (! empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return $user;
    }

    public function findOrCreateUserViaSocial(ProviderUser $providerUser, string $provider): User
    {
        $socialAccount = UserSocial::where('provider', $provider)
            ->where('provider_user_id', $providerUser->getId())
            ->first();

        if ($socialAccount) {
            return $socialAccount->user;
        }

        $user = User::where('email', $providerUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $providerUser->getName() ?? $providerUser->getNickname() ?? 'Без імені',
                'email' => $providerUser->getEmail(),
                'avatar_url' => $providerUser->getAvatar(),
                'email_verified_at' => now(),
            ]);

            $user->roles()->sync(self::DEFAULT_ROLE_TO_USER);
        }

        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_user_id' => $providerUser->getId(),
            'avatar_url' => $providerUser->getAvatar(),
        ]);

        return $user;
    }

    public function createUserFromGoogle(): ?User
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            if ($existingUser->google_id !== $googleUser->getId()) {
                return null;
            }

            $user = $existingUser;
        } else {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar_url' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]);

            $user->roles()->sync(self::DEFAULT_ROLE_TO_USER);
        }

        return $user;
    }

    public function linkSocialAccount(User $user, $providerUser, string $provider): void
    {
        $user->socialAccounts()->updateOrCreate(
            ['provider' => $provider, 'provider_user_id' => $providerUser->getId()],
            ['avatar_url' => $providerUser->getAvatar()]
        );
    }

    public function unlinkSocialAccount(User $user, string $provider): void
    {
        $user->socialAccounts()->where('provider', $provider)->delete();
    }

    public function updateUser(User $user, array $data): User
    {
        $user->name = $data['name'];
        $user->email = strtolower($data['email']);
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->locale = $data['locale'];
        $user->email_verified_at = $data['active'] ? now() : null;

        $user->save();

        return $user;
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    public function restoreUser(int $id): User
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return $user;
    }

    public function getUserPermissions(User $user): Collection
    {
        return $user->roles()->with('permissions')->get()
            ->flatMap(fn ($role) => $role->permissions)
            ->map(fn ($permission) => "{$permission->object}.{$permission->operation}")
            ->unique();
    }

    public function getFilteredPaginatedUsers(array $params): LengthAwarePaginator
    {
        $filter = new UserFilter($params);

        return User::query()
            ->with('roles')
            ->withTrashed()
            ->filter($filter)
            ->orderBy($params['sort_by'], $params['sort_order'])
            ->paginate($params['per_page']);
    }
}
