<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    //untuk menampilkan halaman login
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        logger('Login successful, user: ' . Auth::id() . ', redirecting to dashboard');
        logger('Session data after login: ' . json_encode(session()->all()));

        // Selalu redirect ke dashboard setelah login
        return redirect()->route('dashboard.index');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        logger('Logout called, user: ' . (Auth::id() ?? 'null'));
        Auth::guard('web')->logout(); //Mengeluarkan user dari sistem dengan menghapus data autentikasi dari guard web.

        $request->session()->invalidate(); //Menghapus semua data session dari browser.

        $request->session()->regenerateToken(); //Membuat ulang CSRF token baru untuk keamanan (supaya token lama tidak disalahgunakan).

        return redirect('/login');
    }
}
