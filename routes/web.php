<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('landing');
});

// Dashboard routes — harus login
Route::middleware(['auth'])->group(function () {

    // Dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // DSS Prediksi Profit
    Route::get('/dashboard/dss',      [DashboardController::class, 'dss'])->name('dashboard.dss');
    Route::post('/dashboard/predict', [DashboardController::class, 'predict'])->name('dashboard.predict');

    // Profile (dari Breeze — jangan dihapus)
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
