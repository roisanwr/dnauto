<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home; // Panggil komponen kita

// Ubah route '/' menjadi komponen Home
Route::get('/', Home::class)->name('home');
use App\Http\Controllers\GoogleAuthController;

// Route Google Auth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// Route Logout (Supaya tombol keluar berfungsi)
Route::get('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/');
})->name('logout');