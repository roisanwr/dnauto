<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // BAGIAN 1: SISTEM LARAVEL (Pindahan dari file bawaan)
        // ==========================================
        
        // 1. Users (Login System)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            // Custom Columns DN Auto
            $table->enum('role', ['customer', 'admin'])->default('customer');
            $table->string('no_hp')->nullable();
            $table->timestamps();
        });

        // 2. Password Reset Tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Sessions (Wajib ada biar bisa Login di Web/PWA)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // ==========================================
        // BAGIAN 2: APLIKASI DN AUTO (Bisnis Logic)
        // ==========================================

        // 4. Employees (Teknisi)
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai');
            $table->string('jabatan')->default('Teknisi'); 
            $table->string('kontak'); 
            $table->enum('status_ketersediaan', ['available', 'busy', 'cuti'])->default('available');
            $table->timestamps();
        });

        // 5. Addresses (Buku Alamat)
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('label_alamat'); 
            $table->string('nama_penerima');
            $table->string('no_hp_penerima');
            $table->text('alamat_lengkap');
            $table->string('kota');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // 6. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->string('kategori');
            $table->integer('estimasi_hari_kerja')->default(2);
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        // 7. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_order')->unique();
            $table->foreignId('user_id')->constrained('users');
            
            // Snapshot Data
            $table->string('snap_nama_penerima');
            $table->string('snap_no_hp');
            $table->text('snap_alamat_lengkap');
            
            // Status
            $table->enum('status', [
                'menunggu_dp', 'verifikasi_dp', 'diproses', 
                'siap_dikirim', 'menunggu_pelunasan', 'verifikasi_pelunasan', 
                'dijadwalkan', 'selesai', 'batal'
            ])->default('menunggu_dp');

            // Keuangan
            $table->decimal('total_belanja', 12, 2);
            $table->decimal('biaya_layanan', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->decimal('sisa_tagihan', 12, 2);
            
            $table->boolean('butuh_pemasangan')->default(false);
            $table->timestamps();
        });

        // 8. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('jumlah');
            $table->decimal('harga_saat_beli', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->text('catatan_custom')->nullable();
            $table->timestamps();
        });

        // 9. Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->enum('tipe', ['dp', 'pelunasan']);
            $table->string('metode_pembayaran');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->string('bukti_foto')->nullable();
            $table->enum('status', ['pending', 'valid', 'invalid'])->default('pending');
            $table->timestamps();
        });

        // 10. Schedules
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade')->unique(); 
            $table->foreignId('employee_id')->nullable()->constrained('employees'); 
            $table->date('tgl_pengerjaan');
            $table->time('jam_mulai')->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'reschedule'])->default('terjadwal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Hapus tabel urutan terbalik
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};