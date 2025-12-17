<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Livewire\Home;  // Pastikan ini di-import
use App\Livewire\Login; // Pastikan ini di-import (PENTING)
use App\Livewire\Register; // Pastikan ini di-import (PENTING)
use App\Livewire\Profile;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Produk\Index;
use App\Livewire\Admin\Produk\Create;
use App\Livewire\Admin\Produk\Edit;
use App\Livewire\Admin\Pegawai\Index as PegawaiIndex;
use App\Livewire\Admin\Pegawai\Create as PegawaiCreate;
use App\Livewire\Admin\Pegawai\Edit as PegawaiEdit;
use App\Livewire\Admin\Pelanggan\Index as PelangganIndex;
use App\Livewire\ProdukDetail;
use App\Livewire\Checkout;
use App\Livewire\Payment;
use App\Livewire\History;
use App\Livewire\Admin\Pesanan\Index as PesananIndex;
use App\Livewire\Admin\Pesanan\Show as PesananShow;
use App\Http\Controllers\Admin\InvoiceController;
use App\Livewire\Admin\Schedule\Index as ScheduleIndex;


// 1. Halaman Utama (Home)
Route::get('/', Home::class)->name('home');

// Route Register Baru
Route::get('/register', Register::class)->name('register')->middleware('guest');

// name('login') penting karena Laravel otomatis mencarinya jika user belum login
Route::get('/login', Login::class)->name('login')->middleware('guest');

// 3. Google Auth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// 5. Profile User
Route::get('/profile', Profile::class)->name('profile')->middleware('auth');

// 4. Logout
// Route Logout yang baru (hanya untuk user yang sudah terautentikasi)
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout')->middleware('auth');

// 6. Detail Produk
Route::get('/produk/{id}', ProdukDetail::class)->name('produk.detail');

// 7. Checkout
Route::get('/checkout/{produkId}', Checkout::class)->name('checkout');

// 8. Payment
Route::get('/payment/{id}', App\Livewire\Payment::class)->name('payment');

// 9. History
Route::get('/riwayat', History::class)->name('history')->middleware('auth');

// 10. Detail Pesanan (hanya untuk user yang sudah login)
Route::get('/pesanan/{id}', PesananShow::class)->name('admin.pesanan.show');

// WILAYAH KEKUASAAN ADMIN (Dipagari Middleware is_admin)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    
    // Semua route di sini otomatis ada awalan /admin
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
    
    // Nanti tambah route kelola barang, pesanan, dll disini...
    Route::get('/produk', App\Livewire\Admin\Produk\Index::class)->name('admin.produk');

    // Route untuk halaman tambah produk
    Route::get('/produk/create', Create::class)->name('admin.produk.create');

    // Route untuk halaman edit produk
    Route::get('/produk/{id}/edit', Edit::class)->name('admin.produk.edit');

    // Route Pegawai
    Route::get('/pegawai', PegawaiIndex::class)->name('admin.pegawai');
    Route::get('/pegawai/create', PegawaiCreate::class)->name('admin.pegawai.create');
    Route::get('/pegawai/{id}/edit', PegawaiEdit::class)->name('admin.pegawai.edit');


    // Route Pelanggan
    Route::get('/pelanggan', PelangganIndex::class)->name('admin.pelanggan');

    // Route Pesanan
    Route::get('/pesanan', PesananIndex::class)->name('admin.pesanan');


    // Route Cetak Invoice
    Route::get('/pesanan/{id}/invoice', [InvoiceController::class, 'print'])->name('admin.pesanan.invoice');

    // Route Jadwal
    Route::get('/schedule', App\Livewire\Admin\Schedule\Index::class)->name('admin.schedule');

}); 