<?php

namespace App\Http\Controllers;

use Exception;
use Midtrans\Config;
use Midtrans\CoreApi;
use App\Models\Branch;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Product; // Tambahkan ini jika menggunakan produk
use App\Models\Stock; // Tambahkan ini jika menggunakan model Stock

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

    public function createCashTransaction(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.type' => 'required|in:service,product',
            'branch_id' => 'required|integer',
            'amount_paid' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0'
        ]);

        try {
            // Ensure user is authenticated
            $user = Auth::user();
            if (!$user) {
                throw new \Exception('User not authenticated.');
            }
            // Logging (optional, bisa dihapus jika tidak perlu)
            Log::info('Authenticated User: ' . $user->name);
            Log::info('Auth ID: ' . $user->id);
            Log::info('Session ID: ' . session()->getId());

            DB::beginTransaction();

            // Ambil data cabang dari user yang sedang login
            $branch = $user->branch;
            $branchId = $branch ? $branch->id : $request->branch_id;
            $branchCode = $branch ? $branch->code : 'UNASSIGNED';
            $branchName = $branch ? $branch->name : 'UNASSIGNED';
            // Format invoice number: KodeCabang-NamaCabangSingkat-TIMESTAMP-RANDOM
            $invoiceNumber = strtoupper($branchCode) . '-' . strtoupper(Str::slug($branchName, '_')) . '-' . time() . '-' . Str::random(5);

            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $user->id,
                'branch_id' => $branchId,
                'total_amount' => $request->total_amount,
                'amount_paid' => $request->amount_paid,
                'payment_method' => 'cash',
                'payment_status' => 'success', // status cash = success
                'transaction_date' => now()
            ]);

            // Process items
            foreach ($request->items as $item) {
                if ($item['type'] === 'service') {
                    $service = Service::find($item['id']);
                    if (!$service) {
                        throw new \Exception('Service not found');
                    }

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'service_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $service->price,
                        'subtotal' => $service->price * $item['quantity']
                    ]);
                } else {
                    $product = Product::find($item['id']);
                    if (!$product) {
                        throw new \Exception('Product not found');
                    }

                    // Pengecekan stok sebelum transaksi
                    $stock = Stock::where('product_id', $product->id)
                        ->where('branch_id', $branchId)
                        ->first();

                    if (!$stock || $stock->quantity < $item['quantity']) {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi. Stok tersedia: " . ($stock ? $stock->quantity : 0));
                    }

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $product->price * $item['quantity']
                    ]);
                    // Kurangi stok produk di cabang terkait (khusus cash)
                    Stock::where('product_id', $product->id)
                        ->where('branch_id', $branchId)
                        ->decrement('quantity', $item['quantity']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi tunai berhasil dibuat',
                'invoice_number' => $invoiceNumber,
                'total_amount' => $request->total_amount,
                'transaction_status' => 'completed'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create cash transaction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi tunai: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.type' => 'required|string|in:service,product',
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

            // Proses Items (Layanan dan Produk)
            foreach ($request->items as $itemData) {
                $id = $itemData['id'];
                $quantity = $itemData['quantity'];
                $type = $itemData['type'];

                if ($type === 'service') {
                    $item = Service::find($id);
                    if (!$item) {
                        throw new Exception("Layanan dengan ID {$id} tidak ditemukan.");
                    }
                    $subtotal = $item->price * $quantity;
                    $totalAmount += $subtotal;

                    $transactionDetails[] = [
                        'service_id' => $item->id,
                        'quantity' => $quantity,
                        'price' => $item->price,
                        'subtotal' => $subtotal,
                        'type' => 'service',
                    ];
                } elseif ($type === 'product') {
                    $item = Product::find($id);
                    if (!$item) {
                        throw new Exception("Produk dengan ID {$id} tidak ditemukan.");
                    }

                    // Pengecekan stok sebelum transaksi
                    $stock = Stock::where('product_id', $item->id)
                        ->where('branch_id', $branchId)
                        ->first();

                    if (!$stock || $stock->quantity < $quantity) {
                        throw new Exception("Stok produk {$item->name} tidak mencukupi. Stok tersedia: " . ($stock ? $stock->quantity : 0));
                    }

                    $subtotal = $item->price * $quantity;
                    $totalAmount += $subtotal;

                    $transactionDetails[] = [
                        'product_id' => $item->id,
                        'quantity' => $quantity,
                        'price' => $item->price,
                        'subtotal' => $subtotal,
                        'type' => 'product',
                    ];
                }
            }


            $transaction = Transaction::create([
                'user_id' => $user->id,
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
                    $item = null;
                    $itemId = null;

                    if ($detail['type'] === 'service') {
                        $item = Service::find($detail['service_id']);
                        $itemId = $detail['service_id'];
                    } elseif ($detail['type'] === 'product') {
                        $item = Product::find($detail['product_id']);
                        $itemId = $detail['product_id'];
                    }

                    if (!$item) {
                        throw new Exception("Item dengan ID {$itemId} dan tipe {$detail['type']} tidak ditemukan.");
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
            \Illuminate\Support\Facades\Log::error('Error creating transaction: ' . $e->getMessage() . '\nStack Trace: ' . $e->getTraceAsString());
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
