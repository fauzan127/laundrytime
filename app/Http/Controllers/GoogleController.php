<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    // Redirect ke Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback dari Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();


            $user = User::updateOrCreate([
                'email' => $googleUser->getEmail(),
            ], [
                'name' => $googleUser->getName(),
                'password' => bcrypt('google_' . $googleUser->getId()), // password dummy
            ]);

            Auth::login($user);

            return redirect()->route('dashboard'); // ganti sesuai route dashboard kamu
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login Google gagal, coba lagi.');
        }
    }
}
