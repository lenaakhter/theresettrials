<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with([
                'prompt' => 'select_account consent',
            ])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('login')->with('error', 'Google login failed. Please try again.');
        }

        return $this->findOrCreateUser($googleUser, 'google');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            return redirect('login')->with('error', 'Facebook login failed. Please try again.');
        }

        return $this->findOrCreateUser($facebookUser, 'facebook');
    }

    private function findOrCreateUser($socialUser, $provider)
    {
        // Check if user exists by email
        $user = User::where('email', $socialUser->getEmail())->first();
        $socialName = $socialUser->getName() ?: 'Member';
        $baseUsername = $socialUser->getNickname()
            ?: Str::before($socialUser->getEmail() ?? '', '@')
            ?: Str::slug($socialName, '_');

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $socialName,
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(str()->random(24)), // Random password since they're using OAuth
                'display_name' => $socialName,
                'username' => $this->makeUniqueUsername($baseUsername),
                'username_changed_at' => now(),
                'email_notifications_opt_in' => false,
            ]);
        }

        Auth::login($user, true);

        if (! $user->hasRequiredProfileInfo()) {
            return redirect()->route('profile.complete.show');
        }

        return redirect()->route('home');
    }

    private function makeUniqueUsername(string $seed, ?int $ignoreUserId = null): string
    {
        $base = Str::of($seed)
            ->lower()
            ->replaceMatches('/[^a-z0-9_]+/', '_')
            ->trim('_')
            ->substr(0, 24)
            ->value();

        if (blank($base)) {
            $base = 'member';
        }

        $candidate = $base;
        $counter = 1;

        while (User::query()
            ->when($ignoreUserId, fn ($query) => $query->where('id', '!=', $ignoreUserId))
            ->where('username', $candidate)
            ->exists()) {
            $candidate = Str::limit($base, 24, '').'_'.Str::padLeft((string) $counter, 2, '0');
            $counter++;
        }

        return $candidate;
    }
}
