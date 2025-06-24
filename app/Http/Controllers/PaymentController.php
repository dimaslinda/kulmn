<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\TransactionDetail;
use App\Models\Product; // Tambahkan ini jika menggunakan produk
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\CoreApi;
use Exception;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans (pastikan di .env sudah ada keys)
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'service_ids' => 'required_without:product_ids|array', // Salah satu harus ada
            'service_ids.*' => 'exists:services,id',
            'product_ids' => 'required_without:service_ids|array', // Salah satu harus ada
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'required|array', // Kuantitas untuk layanan atau produk
            'quantities.*' => 'required|integer|min:1',
            'branch_id' => 'required|exists:branches,id', // Validasi branch_id dari frontend
        ]);

        $user = Auth::user();

        // Validasi tambahan di backend: user harus punya branch_id dan sesuai dengan yang dikirim
        if (!$user->branch || $user->branch->id != $request->branch_id) {
            return response()->json(['message' => 'ID Cabang tidak valid atau tidak sesuai dengan akun Anda.'], 403);
        }

        // Ambil data cabang dari user yang sedang login
        $branchId = $user->branch->id;
        $branchCode = $user->branch->code;
        $branchName = $user->branch->name; // Ambil nama cabang

        try {
            $transaction_id = Str::uuid()->toString();
            // Format invoice number: KodeCabang-NamaCabangSingkat-TIMESTAMP-RANDOM
            $invoice_number = strtoupper($branchCode) . '-' . strtoupper(Str::slug($branchName, '_')) . '-' . time() . '-' . Str::random(5);

            $totalAmount = 0;
            $transactionDetails = [];

            // Proses Layanan
            if ($request->has('service_ids') && is_array($request->service_ids)) {
                foreach ($request->service_ids as $key => $serviceId) {
                    $service = Service::find($serviceId);
                    if (!$service) {
                        throw new Exception("Layanan dengan ID {$serviceId} tidak ditemukan.");
                    }
                    $quantity = $request->quantities[$key]; // Kuantitas ini akan selalu 1
                    $subtotal = $service->price * $quantity;
                    $totalAmount += $subtotal;

                    $transactionDetails[] = [
                        'service_id' => $service->id,
                        'quantity' => $quantity,
                        'price' => $service->price,
                        'subtotal' => $subtotal,
                        'type' => 'service',
                    ];
                }
            }

            // Proses Produk (jika ada)
            if ($request->has('product_ids') && is_array($request->product_ids)) {
                foreach ($request->product_ids as $key => $productId) {
                    $product = Product::find($productId);
                    if (!$product) {
                        throw new Exception("Produk dengan ID {$productId} tidak ditemukan.");
                    }
                    $quantity = $request->quantities[$key + (count($request->service_ids ?? []))]; // Ambil kuantitas yang sesuai
                    $subtotal = $product->price * $quantity;
                    $totalAmount += $subtotal;

                    $transactionDetails[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                        'type' => 'product',
                    ];

                    // TODO: Implementasi pengurangan stok di sini atau setelah notifikasi Midtrans (Webhook)
                    // Misalnya: Stock::where('product_id', $product->id)->where('branch_id', $branchId)->decrement('quantity', $quantity);
                }
            }


            $transaction = Transaction::create([
                'branch_id' => $branchId,
                'invoice_number' => $invoice_number,
                'total_amount' => $totalAmount,
                'payment_method' => 'QRIS',
                'payment_status' => 'pending',
                'midtrans_order_id' => $transaction_id,
            ]);

            foreach ($transactionDetails as $detail) {
                $transaction->transactionDetails()->create($detail);
            }

            $midtrans_params = [
                'transaction_details' => [
                    'order_id' => $transaction->midtrans_order_id,
                    'gross_amount' => $totalAmount,
                ],
                'item_details' => array_map(function ($detail) {
                    if ($detail['type'] === 'service') {
                        $item = Service::find($detail['service_id']);
                    } else { // 'product'
                        $item = Product::find($detail['product_id']);
                    }

                    return [
                        'id' => (string) $item->id, // ID item
                        'price' => (int) $item->price, // Pastikan integer
                        'quantity' => (int) $detail['quantity'], // Pastikan integer
                        'name' => (string) $item->name,
                    ];
                }, $transactionDetails),
                'customer_details' => [
                    'first_name' => $user->name,
                    'last_name' => $branchName, // Menggunakan nama cabang sebagai last_name customer
                    'email' => $user->email,
                    'phone' => '081234567890', // Default
                ],
                'callbacks' => [
                    'finish' => url('/payment/success/' . $transaction->invoice_number),
                    'error' => url('/payment/failed/' . $transaction->invoice_number),
                    'pending' => url('/payment/pending/' . $transaction->invoice_number),
                ],
                'payment_type' => 'qris',
                'qris' => [
                    'acquirer' => 'gopay',
                ]
            ];

            $midtrans_charge_response = CoreApi::charge($midtrans_params);

            if ($midtrans_charge_response && $midtrans_charge_response->status_code == '201') {
                $transaction->midtrans_transaction_id = $midtrans_charge_response->transaction_id;
                $transaction->payment_status = $midtrans_charge_response->transaction_status;
                $transaction->midtrans_qr_code_url = $midtrans_charge_response->actions[0]->url;
                $transaction->save();

                return response()->json([
                    'message' => 'Transaksi berhasil dibuat, menunggu pembayaran QRIS.',
                    'invoice_number' => $invoice_number,
                    'total_amount' => $totalAmount,
                    'qr_code_url' => $midtrans_charge_response->actions[0]->url,
                    'transaction_status' => $midtrans_charge_response->transaction_status,
                ], 201);
            } else {
                \Illuminate\Support\Facades\Log::error('Midtrans QRIS Charge Failed: ' . json_encode($midtrans_charge_response));
                $transaction->payment_status = 'failed';
                $transaction->save();
                return response()->json(['message' => 'Gagal membuat transaksi QRIS dengan Midtrans.', 'details' => $midtrans_charge_response], 500);
            }
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating transaction: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat membuat transaksi.', 'error' => $e->getMessage()], 500);
        }
    }

    public function paymentSuccess($invoice_number)
    {
        $transaction = Transaction::where('invoice_number', $invoice_number)->first();
        if ($transaction) {
            return view('payment_status', ['status' => 'success', 'transaction' => $transaction]);
        }
        return redirect('/')->with('error', 'Transaksi tidak ditemukan.');
    }

    public function paymentFailed($invoice_number)
    {
        $transaction = Transaction::where('invoice_number', $invoice_number)->first();
        if ($transaction) {
            return view('payment_status', ['status' => 'failed', 'transaction' => $transaction]);
        }
        return redirect('/')->with('error', 'Transaksi tidak ditemukan.');
    }

    public function paymentPending($invoice_number)
    {
        $transaction = Transaction::where('invoice_number', $invoice_number)->first();
        if ($transaction) {
            return view('payment_status', ['status' => 'pending', 'transaction' => $transaction]);
        }
        return redirect('/')->with('error', 'Transaksi tidak ditemukan.');
    }

    public function getTransactionStatus($invoice_number)
    {
        $transaction = Transaction::where('invoice_number', $invoice_number)->first();

        if (!$transaction) {
            return response()->json(['status' => 'not_found', 'message' => 'Transaksi tidak ditemukan.'], 404);
        }

        return response()->json([
            'status' => $transaction->payment_status,
            'invoice_number' => $transaction->invoice_number,
            'payment_status' => $transaction->payment_status,
            'total_amount' => $transaction->total_amount,
            'qr_code_url' => $transaction->midtrans_qr_code_url,
        ]);
    }
}
