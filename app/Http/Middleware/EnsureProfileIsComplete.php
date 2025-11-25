<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Apply only if user is logged in and logged in via Google (has google_id)
        if ($user && $user->google_id) {
            // Check if profile is incomplete
            if (empty($user->phone) || empty($user->address)) {
                // Allow access to profile edit/update or logout routes to avoid redirect loops
                if ($request->routeIs('profile.edit') || 
                    $request->routeIs('profile.update') || 
                    $request->routeIs('logout')) {
                    return $next($request);
                }

                // Redirect to profile edit page if incomplete
                return redirect()->route('profile.edit')->with('warning', 'Silakan lengkapi profil Anda terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
