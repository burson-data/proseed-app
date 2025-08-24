<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProjectIsSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika session proyek belum ada DAN user TIDAK sedang mengakses halaman:
        // 1. 'Select Project' itu sendiri
        // 2. ATAU halaman 'Manage Projects'
        if (
            !session()->has('current_project_id') &&
            !$request->routeIs('projects.select*') &&
            !$request->routeIs('projects.*') // <-- INI TAMBAHAN PENTINGNYA
        ) {
            return redirect()->route('projects.select');
        }

        return $next($request);
    }
}