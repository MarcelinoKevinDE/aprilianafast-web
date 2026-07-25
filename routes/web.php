<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\ScanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ---------------------------------------------------------------
// PUBLIC — Landing Page
// ---------------------------------------------------------------
Route::get('/', function () {
    return view('index');
})->name('home');

// ---------------------------------------------------------------
// PUBLIC — Booking / Reservasi
// ---------------------------------------------------------------
Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'create'])->name('create');
    Route::post('/', [BookingController::class, 'store'])->name('store');
    Route::get('/{booking}/success', [BookingController::class, 'success'])->name('success');
    Route::get('/{booking}/qr-download', [BookingController::class, 'downloadQr'])->name('qr.download');
});

// ---------------------------------------------------------------
// ADMIN — Auth
// ---------------------------------------------------------------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // ---------------------------------------------------------------
    // ADMIN — Protected Area
    // ---------------------------------------------------------------
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::patch('/booking/{booking}/status', [DashboardController::class, 'updateStatus'])->name('booking.status');

        Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
        Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
        Route::put('/layanan/{layanan}', [LayananController::class, 'update'])->name('layanan.update');
        Route::delete('/layanan/{layanan}', [LayananController::class, 'destroy'])->name('layanan.destroy');

        Route::get('/scan', [ScanController::class, 'index'])->name('scan');
        Route::post('/scan/verify', [ScanController::class, 'verify'])->name('scan.verify');
    });
});