<?php

namespace App\Http\Controllers\Auth;

use App\Http\Services\Users\UserService;
use App\Models\Users\UserSocial;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialAuthController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {

        $this->userService = $userService;
    }

    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback(string $provider)
    {
        $providerUser = Socialite::driver($provider)->stateless()->user();
        $existingAccount = UserSocial::where('provider', $provider)
            ->where('provider_user_id', $providerUser->getId())
            ->first();

        if ($existingAccount && $existingAccount->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Цей акаунт вже прив’язаний до іншого користувача.');
        }

        $this->userService->linkSocialAccount(auth()->user(), $providerUser, $provider);

        return redirect()->back()->with('success', 'Соцмережу успішно прив’язано!');
    }

    public function disconnect(string $provider)
    {
        auth()->user()->socialAccounts()->where('provider', $provider)->delete();

        return redirect()->back()->with('success', 'Соцмережу відв’язано.');
    }
}
