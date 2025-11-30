<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // BAGIAN 1: SISTEM LARAVEL (JANGAN DIUBAH)
        // ==========================================
        
        // 1. Users (Tetap bahasa Inggris agar kompatibel dengan Auth Laravel)
        // Di ERD: Entity 'User'
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Di ERD: nama
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            // Custom Columns
            $table->enum('role', ['customer', 'admin'])->default('customer');
            $table->string('no_hp')->nullable();
            $table->timestamps();
        });

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

        // ==========================================
        // BAGIAN 2: APLIKASI DN AUTO (SESUAI ERD)
        // ==========================================

        // 4. Tabel Pegawai (Employees -> Pegawai)
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai'); // Di ERD: nama
            $table->string('jabatan')->default('Teknisi'); 
            $table->string('kontak'); 
            $table->enum('status_ketersediaan', ['available', 'busy', 'cuti'])->default('available');
            $table->timestamps();
        });

        // 5. Tabel Alamat (Addresses -> Alamat)
        Schema::create('alamat', function (Blueprint $table) {
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

        // 6. Tabel Produk (Products -> Produk)
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk'); // Di ERD: nama
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->string('kategori');
            $table->integer('estimasi_hari_kerja')->default(2);
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        // 7. Tabel Pesanan (Orders -> Pesanan)
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_order')->unique();
            $table->foreignId('user_id')->constrained('users');
            
            // Snapshot Data
            $table->string('snap_nama_penerima');
            $table->string('snap_no_hp');
            $table->text('snap_alamat_lengkap');
            
            // Status (Bahasa Indonesia sesuai BPMN)
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

        // 8. Tabel Detail Pesanan (Order Items -> Detail_Pesanan)
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            // Perhatikan: constrained('pesanan') merujuk ke nama tabel baru
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk');
            $table->integer('jumlah');
            $table->decimal('harga_saat_beli', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->text('catatan_custom')->nullable();
            $table->timestamps();
        });

        // 9. Tabel Pembayaran (Payments -> Pembayaran)
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->enum('tipe', ['dp', 'pelunasan']);
            $table->string('metode_pembayaran');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->string('bukti_foto')->nullable();
            $table->enum('status', ['pending', 'valid', 'invalid'])->default('pending');
            $table->timestamps();
        });

        // 10. Tabel Schedule (Schedules -> Schedule)
        // Saya pakai 'schedule' (singular) agar persis tulisan di kotak ERD kamu
        Schema::create('schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade')->unique(); 
            // Relasi ke tabel pegawai
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai'); 
            $table->date('tgl_pengerjaan');
            $table->time('jam_mulai')->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'reschedule'])->default('terjadwal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Urutan drop dibalik
        Schema::dropIfExists('schedule');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('alamat');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};