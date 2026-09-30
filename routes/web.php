<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
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
});
