<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran; 
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Str; // Tambah ini

class PaymentCallbackController extends Controller
{
    public function receive()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            $notif = new Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $midtransOrderId = $notif->order_id; // Ini bisa jadi INV-123 atau INV-123-PL-999
            $fraud = $notif->fraud_status;

            // --- LOGIC PEMBERSIHAN ID ---
            // Kita harus cari Nomor Order Asli di Database
            // Hapus suffix "-PL-..." kalau ada
            $realOrderNumber = $midtransOrderId;
            
            if (Str::contains($midtransOrderId, '-PL-')) {
                // Ambil bagian sebelum '-PL-'
                $parts = explode('-PL-', $midtransOrderId);
                $realOrderNumber = $parts[0]; 
            }
            // ---------------------------

            // Cari Pesanan pakai Nomor Asli
            $pesanan = Pesanan::where('nomor_order', $realOrderNumber)->lockForUpdate()->first();

            if (!$pesanan) {
                return response()->json(['message' => 'Order not found: ' . $realOrderNumber], 404);
            }

            // Tentukan Status Midtrans
            $midtransStatus = '';
            if ($transaction == 'capture') {
                $midtransStatus = ($fraud == 'challenge') ? 'challenge' : 'success';
            } elseif ($transaction == 'settlement') {
                $midtransStatus = 'success';
            } elseif ($transaction == 'pending') {
                $midtransStatus = 'pending';
            } elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                $midtransStatus = 'failed';
            }

            // --- LOGIC UPDATE STATUS DN AUTO ---
            
            if ($midtransStatus == 'success') {
                
                // 1. CEK: Apakah ini Pelunasan?
                // Tandanya: Status pesanan 'menunggu_pelunasan' ATAU Order ID mengandung '-PL-'
                if ($pesanan->status == 'menunggu_pelunasan' || Str::contains($midtransOrderId, '-PL-')) {
                    
                    $pesanan->update([
                        'status' => 'lunas', // Atau 'siap_dikirim'/'siap_dipasang' tergantung flow kamu
                        'sisa_tagihan' => 0,
                        // Kalau ini order jasa, ubah ke siap_dipasang
                        // Kalau order kirim, ubah ke siap_dikirim (opsional logic disini)
                    ]);
                    
                    // Khusus logic otomatis status akhir
                    if ($pesanan->butuh_pemasangan) {
                        $pesanan->update(['status' => 'siap_dipasang']);
                    } else {
                        $pesanan->update(['status' => 'siap_dikirim']);
                    }

                    Pembayaran::create([
                        'pesanan_id' => $pesanan->id,
                        'tipe' => 'pelunasan',
                        'metode_pembayaran' => $type,
                        'jumlah_bayar' => $notif->gross_amount,
                        'status' => 'valid'
                    ]);

                } 
                // 2. Kalau bukan pelunasan, berarti Pembayaran Awal (DP/Full)
                elseif ($pesanan->status == 'menunggu_pembayaran') {
                    
                    $statusBaru = 'produksi'; // Default masuk produksi
                    
                    $pesanan->update([
                        'status' => $statusBaru,
                    ]);
                    
                    // Kalau Full Payment, nol-kan sisa tagihan
                    if($pesanan->jenis_pembayaran == 'lunas') {
                        $pesanan->update(['sisa_tagihan' => 0]);
                    }

                    Pembayaran::create([
                        'pesanan_id' => $pesanan->id,
                        'tipe' => $pesanan->jenis_pembayaran == 'dp' ? 'dp' : 'pelunasan',
                        'metode_pembayaran' => $type,
                        'jumlah_bayar' => $notif->gross_amount,
                        'status' => 'valid'
                    ]);
                }
            } 
            elseif ($midtransStatus == 'failed') {
                // Jangan batalkan order kalau pelunasan gagal, cuma info aja
                if ($pesanan->status == 'menunggu_pembayaran') {
                    $pesanan->update(['status' => 'batal']);
                }
            }

            return response()->json(['message' => 'Callback processed']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}