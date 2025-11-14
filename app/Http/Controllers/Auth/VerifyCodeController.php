<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class VerifyCodeController extends Controller
{
    public function show(Request $request)
    {
        return view('auth.verify-code');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if ($user->verification_code !== $request->code) {
            throw ValidationException::withMessages([
                'code' => 'Kode verifikasi tidak valid.',
            ]);
        }

        if ($user->verification_code_expires_at < now()) {
            throw ValidationException::withMessages([
                'code' => 'Kode verifikasi telah kedaluwarsa.',
            ]);
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi!');
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return back()->withErrors(['email' => 'Email sudah diverifikasi.']);
        }

        // Generate new verification code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->verification_code = $code;
        $user->verification_code_expires_at = now()->addMinutes(10);
        $user->save();

        // Send email with code
        $user->notify(new \App\Notifications\VerifyEmailWithCode($code));

        return back()->with('status', 'verification-code-sent');
    }
}
