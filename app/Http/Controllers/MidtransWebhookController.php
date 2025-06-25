<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Stock; // Tambahkan ini jika menggunakan stok
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Exception;

class MidtransWebhookController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function handler(Request $request)
    {
        try {
            $notif = new Notification();

            $transactionStatus = $notif->transaction_status;
            $fraudStatus = $notif->fraud_status;
            $orderId = $notif->order_id;

            $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

            if (!$transaction) {
                Log::warning('Midtrans Notification: Transaction not found for order ID: ' . $orderId);
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $transaction->payment_status = 'challenge';
                } else if ($fraudStatus == 'accept') {
                    $transaction->payment_status = 'success';
                    // --- PENTING: Logika pengurangan stok di sini setelah pembayaran sukses ---
                    // Contoh:
                    foreach ($transaction->transactionDetails as $detail) {
                        if ($detail->type === 'product' && $detail->product_id) {
                            Stock::where('product_id', $detail->product_id)
                                ->where('branch_id', $transaction->branch_id)
                                ->decrement('quantity', $detail->quantity);
                        }
                    }
                }
            } else if ($transactionStatus == 'settlement') {
                $transaction->payment_status = 'success';
                // --- PENTING: Logika pengurangan stok di sini setelah pembayaran sukses (jika tidak di capture) ---
                // Pastikan tidak dobel dengan capture
                foreach ($transaction->transactionDetails as $detail) {
                    if ($detail->type === 'product' && $detail->product_id) {
                        Stock::where('product_id', $detail->product_id)
                            ->where('branch_id', $transaction->branch_id)
                            ->decrement('quantity', $detail->quantity);
                    }
                }
            } else if ($transactionStatus == 'pending') {
                $transaction->payment_status = 'pending';
            } else if ($transactionStatus == 'deny') {
                $transaction->payment_status = 'failed';
            } else if ($transactionStatus == 'expire') {
                $transaction->payment_status = 'expire';
            } else if ($transactionStatus == 'cancel') {
                $transaction->payment_status = 'cancelled';
            } else if ($transactionStatus == 'refund' || $transactionStatus == 'partial_refund') {
                $transaction->payment_status = 'refunded';
            }

            $transaction->save();

            Log::info('Midtrans Notification Processed for Order ID: ' . $orderId . ' Status: ' . $transaction->payment_status);

            return response()->json(['message' => 'OK'], 200);
        } catch (Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing webhook: ' . $e->getMessage()], 500);
        }
    }
}
