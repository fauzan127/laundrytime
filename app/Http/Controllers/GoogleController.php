<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    // Redirect ke Google
    public function redirectToGoogle()
    {
        $redirectUrl = Socialite::driver('google')->redirect()->getTargetUrl();

        // tambahkan parameter manual
        return redirect($redirectUrl . '&prompt=select_account');
    }


    // Callback dari Google
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())->orWhere('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists, update google_id if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->email_verified_at = now(); // Mark email as verified when linking Google account
                    $user->save();
                }
                Auth::login($user);
                $request->session()->regenerate();
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(uniqid()), // supaya tidak null
                    'email_verified_at' => now(), // Mark email as verified for Google users
                    'phone' => '', // Initialize empty phone
                    'address' => '', // Initialize empty address
                ]);
                Auth::login($user);
                $request->session()->regenerate();
            }
            logger('User logged in:', ['id' => $user->id]);
            return redirect()->route('dashboard.index');
        } catch (\Exception $e) {
            logger('Google login error:', ['error' => $e->getMessage()]);
            return redirect()->route('login')->with('error', 'Login Google gagal, coba lagi.');
        }
    }


}
