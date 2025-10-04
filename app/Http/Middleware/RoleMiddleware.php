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
            return redirect()->route('login');
        }

        // Cek apakah role user saat ini sama dengan role yang diizinkan
        if (Auth::user()->role !== $role) {
            // Redirect ke dashboard yang sesuai jika role-nya ada, tapi salah halaman
            if (Auth::user()->role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role === 'Public') {
                return redirect()->route('public.dashboard');
            }

        }

        return $next($request);
    }
}