<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika user sudah login, arahkan ke dashboard yang sesuai
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'Public') {
                return redirect()->route('public.dashboard');
            }
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        
        // Auth::attempt akan menggunakan Pengguna Model (tabel 'pengguna')
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'Public') {
                return redirect()->route('public.dashboard');
            }
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role === 'Admin') {
            return view('admin.dashboard');
        } elseif ($user->role === 'Public') {
            return view('public.dashboard');
        }
        
        // Fallback jika tidak ada role (seharusnya sudah dicegah oleh middleware)
        return redirect()->route('login');
    }
}