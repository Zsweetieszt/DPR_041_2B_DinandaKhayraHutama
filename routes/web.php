<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\KomponenGajiController;

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
    Route::resource('anggota', AnggotaController::class)->except(['show'])->parameters([
        'anggota' => 'anggota'
    ]);

    // KELOLA KOMPONEN GAJI
    Route::prefix('komponen-gaji')->name('komponen.')->group(function () {
        Route::get('/', [KomponenGajiController::class, 'index'])->name('index');
        Route::get('/create', [KomponenGajiController::class, 'create'])->name('create');
        Route::post('/', [KomponenGajiController::class, 'store'])->name('store');
        
        Route::get('/{id}/edit', [KomponenGajiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KomponenGajiController::class, 'update'])->name('update');
        Route::delete('/{id}', [KomponenGajiController::class, 'destroy'])->name('destroy');
    });
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
    Route::get('/anggota/{anggota}', [AnggotaController::class, 'show'])->name('anggota.show');
});