<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah pengguna sudah login DAN perannya adalah 'admin'
        if (Auth::check() && Auth::user()->role == 'admin') {
            // Jika ya, izinkan request untuk melanjutkan
            return $next($request);
        }

        // Jika tidak, tolak dan kembalikan ke dashboard dengan pesan error
        return redirect('/dashboard')->with('error', 'You do not have permission to access this page.');
    }
}