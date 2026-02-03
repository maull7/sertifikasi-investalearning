<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Arahkan user login Google yang belum melengkapi profil ke halaman lengkapi profil.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->needsProfileCompletion()) {
            return $next($request);
        }

        if ($request->routeIs('profile.complete') || $request->routeIs('profile.complete.update')) {
            return $next($request);
        }

        return redirect()->route('profile.complete');
    }
}
