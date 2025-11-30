<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home; // Panggil komponen kita

// Ubah route '/' menjadi komponen Home
Route::get('/', Home::class)->name('home');