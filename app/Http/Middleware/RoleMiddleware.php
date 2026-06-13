<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Sudah login tapi role tidak sesuai
        if (Auth::user()->role !== $role) {
            // Redirect ke dashboard role yang sesuai
            return redirect()->route(Auth::user()->role . '.dashboard')
                ->with('error', 'Kamu tidak punya akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
