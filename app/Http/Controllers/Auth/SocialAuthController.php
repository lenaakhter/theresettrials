<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
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

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(str()->random(24)), // Random password since they're using OAuth
                'display_name' => $socialUser->getName(),
            ]);
        }

        Auth::login($user, true);

        return redirect('/blogs');
    }
}
