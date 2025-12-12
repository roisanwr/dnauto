<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek 1: Sudah login belum?
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek 2: Role-nya admin bukan?
        if (Auth::user()->role !== 'admin') {
            // Kalau bukan admin, tendang ke homepage (Customer Area)
            return redirect('/'); 
        }

        // Kalau Admin, silakan masuk
        return $next($request);
    }
}