<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect ke halaman login Google.
     */
    public function redirect(): RedirectResponse
    {
        session()->save();

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google setelah login/register.
     */
    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::query()->where('google_id', $googleUser->getId())->first();

        if (! $user) {
            $user = User::query()->where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            } else {
                $user = User::query()->create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null,
                    'email_verified_at' => now(),
                    'role' => 'User',
                    'status_user' => 'Teraktivasi',
                    'jenis_kelamin' => 'Laki-laki',
                ]);
            }
        }

        Auth::login($user, true);

        if ($user->role === 'User' && $user->needsProfileCompletion()) {
            return redirect()->route('profile.complete');
        }

        if ($user->role === 'Admin') {
            return redirect()->route('dashboard');
        }

        return redirect()->intended(route('user.dashboard', absolute: false));
    }
}
