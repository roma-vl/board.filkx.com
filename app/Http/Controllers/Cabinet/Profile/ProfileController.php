<?php

namespace App\Http\Controllers\Cabinet\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\TestEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Account/Profile/Index');
    }

    /**
     * Display the user's profile form.
     */
    public function settings(Request $request): Response
    {
        $user = $request->user()->load('socialAccounts');

        return Inertia::render('Account/Profile/Settings', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'auth' => [
                'user' => $user,
            ],
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        Mail::to($request->user()->email)->send(new TestEmail($request->user()));

        return Redirect::route('account.profile.settings')->with('success', __('profile.profile_information_update'));
    }

    public function uploadAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => [
                'required',
                File::image()->max(5 * 1024), // до 5MB
            ],
        ]);

        $user = $request->user();

        // Видалення попереднього аватара
        if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        $filename = 'avatars/'.Str::uuid().'.'.$request->file('avatar')->getClientOriginalExtension();

        // Зберігаємо новий аватар
        $path = $request->file('avatar')->storeAs('avatars', basename($filename), 'public');

        // Зберігаємо шлях в БД
        $user->avatar_url = $path;
        $user->save();

        return Redirect::back()
            ->with('success', __('profile.avatar_uploaded_successfully'));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
