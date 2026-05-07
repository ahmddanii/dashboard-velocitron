<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard utama — semua role boleh akses
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // DSS — hanya 2 role ini yang boleh operasikan
    Route::middleware(['role:head-analytics,financial-controller'])->group(function () {
        Route::get('/dashboard/dss',      [DashboardController::class, 'dss'])->name('dashboard.dss');
        Route::post('/dashboard/predict', [DashboardController::class, 'predict'])->name('dashboard.predict');
    });

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
