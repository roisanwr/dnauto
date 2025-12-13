<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Models\Pembayaran; // Opsional: kalau mau catat log pembayaran
use Midtrans\Config;
use Midtrans\Notification;

class PaymentCallbackController extends Controller
{
    public function receive()
    {
        // 1. Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            // 2. Tangkap Notifikasi dari Midtrans
            $notif = new Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            // 3. Cari Pesanan Berdasarkan Order ID
            $pesanan = Pesanan::where('nomor_order', $order_id)->first();

            if (!$pesanan) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // 4. Logika Status Transaksi
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $pesanan->update(['status' => 'menunggu_verifikasi']);
                    } else {
                        $pesanan->update(['status' => 'lunas']);
                    }
                }
            } else if ($transaction == 'settlement') {
                // INI YANG PALING PENTING (Sukses Bayar)
                $pesanan->update(['status' => 'lunas']);
            
            } else if ($transaction == 'pending') {
                $pesanan->update(['status' => 'menunggu_pembayaran']);
            
            } else if ($transaction == 'deny') {
                $pesanan->update(['status' => 'gagal']);
            
            } else if ($transaction == 'expire') {
                $pesanan->update(['status' => 'kadaluarsa']);
            
            } else if ($transaction == 'cancel') {
                $pesanan->update(['status' => 'dibatalkan']);
            }

            return response()->json(['message' => 'Callback received successfully']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}