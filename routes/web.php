<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect(Auth::user()->isAdmin() ? '/admin/dashboard' : '/petugas/dashboard');
    }
    return redirect('/login');
});

// Auth Routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes - full access
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // User Management (admin only)
    Route::resource('users', UserController::class);

    // Master Data (admin only - full CRUD)
    Route::resource('dokters', DokterController::class);
    Route::resource('polikliniks', PoliklinikController::class);
    Route::resource('jadwal-dokter', JadwalDokterController::class);

    // Pasien & Registrasi (admin - full access)
    Route::resource('pasien', PasienController::class);
    Route::resource('registrasi', RegistrasiController::class);
    Route::post('registrasi/{registrasi}/cancel', [RegistrasiController::class, 'cancel'])->name('registrasi.cancel');
    Route::get('registrasi/{registrasi}/print', [RegistrasiController::class, 'print'])->name('registrasi.print');
});

// Petugas Routes - limited access
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'petugas'])->name('dashboard');

    // Pasien (petugas - CRUD)
    Route::resource('pasien', PasienController::class);

    // Registrasi (petugas - CRUD)
    Route::resource('registrasi', RegistrasiController::class);
    Route::post('registrasi/{registrasi}/cancel', [RegistrasiController::class, 'cancel'])->name('registrasi.cancel');
    Route::get('registrasi/{registrasi}/print', [RegistrasiController::class, 'print'])->name('registrasi.print');

    // Dokter, Poliklinik, Jadwal (petugas - read only)
    Route::get('dokters', [DokterController::class, 'index'])->name('dokters.index');
    Route::get('polikliniks', [PoliklinikController::class, 'index'])->name('polikliniks.index');
    Route::get('jadwal-dokter', [JadwalDokterController::class, 'index'])->name('jadwal-dokter.index');
});
