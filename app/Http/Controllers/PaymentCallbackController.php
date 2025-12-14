<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Str; // Jangan lupa import ini

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
            $midtransOrderId = $notif->order_id; // Contoh: INV-123-PL-173849
            $fraud = $notif->fraud_status;

            // --- 1. BERSIHKAN ORDER ID ---
            // Kita harus buang embel-embel "-PL-..." biar ketemu di database
            $realOrderId = $midtransOrderId;
            if (Str::contains($midtransOrderId, '-PL-')) {
                $parts = explode('-PL-', $midtransOrderId);
                $realOrderId = $parts[0]; // Ambil "INV-123" nya saja
            }

            // --- 2. CARI PESANAN ---
            $pesanan = Pesanan::where('nomor_order', $realOrderId)->lockForUpdate()->first();

            if (!$pesanan) {
                return response()->json(['message' => 'Order not found: ' . $realOrderId], 404);
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

            // --- 3. UPDATE STATUS DN AUTO ---
            if ($midtransStatus == 'success') {
                
                // LOGIC PELUNASAN
                // Cek apakah ID dari Midtrans mengandung "-PL-" ATAU status memang menunggu pelunasan
                if (Str::contains($midtransOrderId, '-PL-') || $pesanan->status == 'menunggu_pelunasan') {
                    
                    // Update jadi LUNAS (atau siap_dipasang/dikirim)
                    $pesanan->sisa_tagihan = 0; // Pastikan 0
                    
                    if ($pesanan->butuh_pemasangan) {
                        $pesanan->status = 'siap_dipasang';
                    } else {
                        $pesanan->status = 'siap_dikirim';
                    }
                    $pesanan->save();

                    // Catat Log Pembayaran
                    Pembayaran::create([
                        'pesanan_id' => $pesanan->id,
                        'tipe' => 'pelunasan',
                        'metode_pembayaran' => $type,
                        'jumlah_bayar' => $notif->gross_amount,
                        'status' => 'valid'
                    ]);

                } 
                // LOGIC PEMBAYARAN AWAL (DP/FULL)
                elseif ($pesanan->status == 'menunggu_pembayaran') {
                    
                    $pesanan->status = 'produksi'; // Masuk produksi
                    
                    // Kalau full payment, sisa tagihan 0
                    if($pesanan->jenis_pembayaran == 'lunas'){
                        $pesanan->sisa_tagihan = 0;
                    }
                    $pesanan->save();

                    Pembayaran::create([
                        'pesanan_id' => $pesanan->id,
                        'tipe' => $pesanan->jenis_pembayaran == 'dp' ? 'dp' : 'pelunasan',
                        'metode_pembayaran' => $type,
                        'jumlah_bayar' => $notif->gross_amount,
                        'status' => 'valid'
                    ]);
                }
            }
            // Handle Failed/Cancel
            elseif ($midtransStatus == 'failed') {
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