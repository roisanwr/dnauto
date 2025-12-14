<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran; 
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

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
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            // Cari Pesanan
            // Kita pakai 'lockForUpdate' biar gak crash kalau ada request barengan
            $pesanan = Pesanan::where('nomor_order', $order_id)->lockForUpdate()->first();

            if (!$pesanan) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Tentukan Status Midtrans (Simplifikasi logika)
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

            // --- LOGIC UTAMA DN AUTO ---
            
            if ($midtransStatus == 'success') {
                // LOGIC 1: Cek apakah ini pembayaran DP atau Pelunasan?
                // Kita cek 'sisa_tagihan'.
                
                // Kalau sisa tagihan masih penuh (sama dengan grand total atau sesuai skema DP), 
                // berarti ini pembayaran PERTAMA.
                
                if ($pesanan->status == 'menunggu_pembayaran') {
                    // Kasus: Pembayaran Pertama (Entah DP atau Lunas Langsung)
                    
                    if ($pesanan->jenis_pembayaran == 'dp') {
                        // User pilih DP, uang masuk, berarti sekarang status 'diproses'
                        $pesanan->update([
                            'status' => 'produksi',
                            // Sisa tagihan sudah dihitung saat create pesanan, jadi biarkan saja
                        ]);
                    } else {
                        // User pilih Full Payment
                        $pesanan->update([
                            'status' => 'produksi', 
                            'sisa_tagihan' => 0 // Pastikan 0
                        ]);
                    }
                    
                    // Catat ke tabel Pembayaran (History)
                    Pembayaran::create([
                        'pesanan_id' => $pesanan->id,
                        'tipe' => $pesanan->jenis_pembayaran == 'dp' ? 'dp' : 'pelunasan',
                        'metode_pembayaran' => $type,
                        'jumlah_bayar' => $notif->gross_amount,
                        'status' => 'valid'
                    ]);

                } elseif ($pesanan->status == 'menunggu_pelunasan') {
                    // Kasus: Pembayaran Kedua (Pelunasan)
                    $pesanan->update([
                        'status' => 'lunas', // atau 'siap_dikirim'
                        'sisa_tagihan' => 0
                    ]);

                    Pembayaran::create([
                        'pesanan_id' => $pesanan->id,
                        'tipe' => 'pelunasan',
                        'metode_pembayaran' => $type,
                        'jumlah_bayar' => $notif->gross_amount,
                        'status' => 'valid'
                    ]);
                }
            } 
            elseif ($midtransStatus == 'failed') {
                $pesanan->update(['status' => 'batal']);
            }

            return response()->json(['message' => 'Callback processed']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}