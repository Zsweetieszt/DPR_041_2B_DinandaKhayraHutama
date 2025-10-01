<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnggotaController;

/*
|--------------------------------------------------------------------------
| Public Routes (Auth)
|--------------------------------------------------------------------------
*/
// Rute root mengarah ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Admin Routes (Protected by Auth & Role: Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard (landing page setelah login Admin)
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // CRUD Anggota DPR
    // FIX KRITIS: Mendefinisikan nama parameter resource secara eksplisit
    Route::resource('anggota', AnggotaController::class)->except(['show'])->parameters([
        'anggota' => 'anggota' // Memastikan parameter di URL adalah {anggota}
    ]);
});


/*
|--------------------------------------------------------------------------
| Public/Client Routes (Protected by Auth & Role: Public)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Public'])->prefix('public')->name('public.')->group(function () {
    // Dashboard (landing page setelah login Public)
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Read Only Anggota DPR
    Route::get('/anggota', [AnggotaController::class, 'publicIndex'])->name('anggota.index');
    Route::get('/anggota/{anggota}', [AnggotaController::class, 'show'])->name('anggota.show'); // Detail View
});