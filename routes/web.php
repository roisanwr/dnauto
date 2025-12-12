<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Livewire\Home;  // Pastikan ini di-import
use App\Livewire\Login; // Pastikan ini di-import (PENTING)

// 1. Halaman Utama (Home)
Route::get('/', Home::class)->name('home');

// 2. Halaman Login (INI YANG TADI KURANG)
// name('login') penting karena Laravel otomatis mencarinya jika user belum login
Route::get('/login', Login::class)->name('login')->middleware('guest');

// 3. Google Auth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// 4. Logout
// Route Logout yang baru (hanya untuk user yang sudah terautentikasi)
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout')->middleware('auth');