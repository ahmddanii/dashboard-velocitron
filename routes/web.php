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

    // Create Request
    Route::get(
        '/requests/create',
        [DashboardController::class, 'createRequest']
    )->name('requests.create');

    Route::post(
        '/requests/store',
        [DashboardController::class, 'storeRequest']
    )->name('requests.store');

    Route::get(
        '/requests/pending',
        [DashboardController::class, 'pendingRequests']
    )->name('requests.pending');

    Route::get(
        '/requests/{id}/review',
        [DashboardController::class, 'reviewRequest']
    )->name('requests.review');

    Route::post(
        '/requests/{id}/approve',
        [DashboardController::class, 'approveRequest']
    )->name('requests.approve');

    Route::post(
        '/requests/{id}/reject',
        [DashboardController::class, 'rejectRequest']
    )->name('requests.reject');

    Route::get(
        '/transactions/history',
        [
            DashboardController::class,
            'transactionHistory'
        ]
    )->name('transactions.history');

    Route::get(
        '/transactions/export',
        [
            DashboardController::class,
            'exportTransactions'
        ]
    )->name('transactions.export');
    Route::get(
        '/analytics/export',
        [
            DashboardController::class,
            'exportAnalyticsReport'
        ]
    )->name('analytics.export');
});

require __DIR__ . '/auth.php';
