<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations (Membuat semua tabel dan struktur yang diperlukan).
     */
    public function up(): void
    {
        // ==========================================
        // BAGIAN 1: SISTEM LARAVEL & USERS
        // ==========================================

        // 1. Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            
            // Support Google Auth & Nullable Password
            $table->string('google_id')->nullable();
            $table->string('avatar')->nullable();
            $table->string('password')->nullable(); 
            
            $table->rememberToken();
            
            // Custom Columns Role & HP
            $table->enum('role', ['customer', 'admin'])->default('customer');
            $table->string('no_hp')->nullable();
            $table->timestamps();
        });

        // 2. Standard Laravel Tokens (Reset Password & Sessions)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Tabel Personal Access Tokens (Untuk API/Sanctum jika nanti butuh)
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });

        // ==========================================
        // BAGIAN 2: APLIKASI DN AUTO
        // ==========================================

        // 3. Tabel Pegawai (Employees)
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai');
            $table->string('jabatan')->default('Teknisi'); 
            $table->string('kontak'); 
            $table->enum('status_ketersediaan', ['available', 'busy', 'cuti'])->default('available');
            $table->timestamps();
        });

        // 4. Tabel Alamat (Addresses)
        Schema::create('alamat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('label_alamat'); // e.g., "Rumah", "Kantor"
            $table->string('nama_penerima');
            $table->string('no_hp_penerima');
            $table->text('alamat_lengkap');
            $table->string('kota'); // Penting untuk logic ongkir Home Service nanti
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // 5. Tabel Produk (Products)
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            
            // [BARU] Kolom Harga Jasa (Sesuai diskusi terakhir)
            // Biar bisa bedain: Harga Barang vs Harga Jasa Pasang
            $table->decimal('harga_jasa', 12, 2)->default(0); 
            
            $table->string('kategori');
            $table->integer('estimasi_hari_kerja')->default(2);
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        // 6. Tabel Pesanan (Orders)
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_order')->unique();
            $table->foreignId('user_id')->constrained('users');
            
            // Snapshot Data Pengiriman (History alamat saat beli)
            $table->string('snap_nama_penerima');
            $table->string('snap_no_hp');
            $table->text('snap_alamat_lengkap');
            
            // KEUANGAN & STATUS (Sesuai Logic DP 50%)
            $table->enum('jenis_pembayaran', ['lunas', 'dp'])->default('lunas');
            $table->string('status')->default('menunggu_pembayaran'); 
            
            $table->decimal('total_belanja', 12, 2);       // Total Harga Barang
            $table->decimal('biaya_layanan', 12, 2)->default(0); // Total Jasa + Ongkir Teknisi
            $table->decimal('grand_total', 12, 2);         // Total Project
            
            // Kolom Kuncian DP
            $table->decimal('jumlah_dp', 12, 2)->default(0); // Nominal 50%
            $table->decimal('sisa_tagihan', 12, 2)->default(0); // Sisa pelunasan
            
            // Midtrans Token
            $table->string('snap_token')->nullable();
            
            $table->boolean('butuh_pemasangan')->default(false);
            $table->timestamps();
        });

        // 7. Tabel Detail Pesanan (Items)
        Schema::create('t_pesanan_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk');
            $table->integer('jumlah');
            $table->decimal('harga_saat_beli', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->text('catatan_custom')->nullable();
            $table->timestamps();
        });

        // 8. Tabel Pembayaran (History Transaksi Midtrans)
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->enum('tipe', ['dp', 'pelunasan']); // Penting buat callback controller
            $table->string('metode_pembayaran');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->string('bukti_foto')->nullable();
            $table->enum('status', ['pending', 'valid', 'invalid'])->default('pending');
            $table->timestamps();
        });

        // 9. Tabel Schedule (Jadwal Pengerjaan)
        Schema::create('schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade')->unique(); 
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai'); 
            $table->date('tgl_pengerjaan');
            $table->time('jam_mulai')->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'reschedule'])->default('terjadwal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations (Menghapus semua tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('t_pesanan_produk');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('alamat');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};