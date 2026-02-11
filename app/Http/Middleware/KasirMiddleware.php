<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KasirMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'kasir') {
            return $next($request);
        }
        
        return redirect('/dashboard')->with('error', 'Akses ditolak! Halaman ini hanya untuk kasir.');
    }
}