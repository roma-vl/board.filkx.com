<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\Users\UserService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class GoogleController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function registerToGoogle(string $provider): SymfonyRedirectResponse|RedirectResponse
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleGoogleCallback(string $provider)
    {
        try {
            $providerUser = Socialite::driver($provider)->stateless()->user();
            $user = $this->userService->findOrCreateUserViaSocial($providerUser, $provider);

            Auth::login($user, true);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            report($e);

            return redirect('/')->with('error', 'Помилка при авторизації через Google.');
        }
    }
}
