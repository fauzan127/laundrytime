<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthOr403Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403);
        }

        $user = Auth::user();

        // Allow access to profile edit and update routes to avoid infinite loops
        if ($request->routeIs('profile.edit') || $request->routeIs('profile.update')) {
            return $next($request);
        }

        // Check if phone and address are filled
        if (empty($user->phone) || empty($user->address)) {
            return redirect()->route('profile.edit')->with('warning', 'Silakan lengkapi profil Anda dengan menambahkan nomor telepon dan alamat sebelum melanjutkan.');
        }

        return $next($request);
    }
}
