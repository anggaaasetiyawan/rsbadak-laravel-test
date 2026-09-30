<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\RegistrasiController;
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

// Dashboard (sementara satu halaman untuk semua role)
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', fn () => view('dashboard'));
    Route::get('/petugas/dashboard', fn () => view('dashboard'));

    Route::resource('dokters', DokterController::class);
    Route::resource('polikliniks', PoliklinikController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('jadwal-dokter', JadwalDokterController::class);
    Route::resource('registrasi', RegistrasiController::class);
    Route::post('registrasi/{registrasi}/cancel', [RegistrasiController::class, 'cancel'])->name('registrasi.cancel');
    Route::get('registrasi/{registrasi}/print', [RegistrasiController::class, 'print'])->name('registrasi.print');
});
