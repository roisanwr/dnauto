<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Str;

class PaymentCallbackController extends Controller
{
    public function receive()
    {
        // 1. Setup Konfigurasi
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            $notif = new Notification();

            // ==========================================
            // SECURITY CHECK: VALIDASI SIGNATURE KEY
            // ==========================================
            // Rumus Midtrans: SHA512(order_id + status_code + gross_amount + ServerKey)
            $localSignature = hash('sha512', $notif->order_id . $notif->status_code . $notif->gross_amount . Config::$serverKey);

            if ($localSignature !== $notif->signature_key) {
                // Jika tidak cocok, tolak request! Ini pasti request palsu.
                return response()->json(['message' => 'Invalid Signature'], 403);
            }
            // ==========================================

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $midtransOrderId = $notif->order_id; 
            $fraud = $notif->fraud_status;

            // --- BERSIHKAN ORDER ID ---
            $realOrderId = $midtransOrderId;
            if (Str::contains($midtransOrderId, '-PL-')) {
                $parts = explode('-PL-', $midtransOrderId);
                $realOrderId = $parts[0]; 
            }

            // --- CARI PESANAN ---
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

            // --- UPDATE STATUS DN AUTO ---
            if ($midtransStatus == 'success') {
                
                // LOGIC PELUNASAN
                if (Str::contains($midtransOrderId, '-PL-') || $pesanan->status == 'menunggu_pelunasan') {
                    
                    $pesanan->sisa_tagihan = 0; 
                    
                    if ($pesanan->butuh_pemasangan) {
                        $pesanan->status = 'siap_dipasang';
                    } else {
                        $pesanan->status = 'siap_dikirim';
                    }
                    $pesanan->save();

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
                    
                    $pesanan->status = 'produksi'; 
                    
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