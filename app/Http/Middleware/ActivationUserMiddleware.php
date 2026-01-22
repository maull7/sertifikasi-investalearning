<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActivationUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::user()->status_user == 'Belum Teraktivasi' && Auth::user()->role != 'Admin') {
            return abort(403, 'Akses ditolak, hanya akun yang sudah di aktivasi bisa mengakses ini');
        }
        return $next($request);
    }
}
