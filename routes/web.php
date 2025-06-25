<?php

use App\Http\Controllers\GeneralControlllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;
use App\Models\Service;

Route::get('/', [GeneralControlllers::class, 'index'])->name('index');
Route::get('/mitra', [GeneralControlllers::class, 'mitra'])->name('mitra');
Route::get('/academy', [GeneralControlllers::class, 'academy'])->name('academy');


Route::middleware(['auth'])->group(function () {

    // Ini adalah rute default Breeze setelah login.
    // Kita arahkan semua user yang landas di sini ke pos.home.
    // Pemisahan admin/non-admin ditangani oleh getLoginRedirectUrl() di AdminPanelProvider.
    Route::get('/dashboard', function () {
        return redirect()->route('pos.home');
    })->name('dashboard');

    // Rute utama aplikasi POS
    Route::get('/pos', function () {
        $user = Auth::user();

        // --- PERBAIKAN: Seluruh blok validasi `if (!$user->branch)` DIHAPUS DARI SINI ---
        // Dengan ini, semua user yang login akan bisa mengakses halaman POS.
        // Penanganan user tanpa cabang (misal admin pusat atau user yang tidak terhubung)
        // akan dilakukan di view pos.blade.php dan di PaymentController untuk aksi pembuatan transaksi.

        return view('pos', [
            // selectedBranchId dan selectedBranchCode akan null jika $user->branch null
            'selectedBranchId' => $user->branch ? $user->branch->id : null,
            'selectedBranchCode' => $user->branch ? $user->branch->code : 'UNASSIGNED', // Placeholder jika tidak ada cabang
        ]);
    })->name('pos.home');

    Route::post('/api/create-cash-transaction', [PaymentController::class, 'createCashTransaction']);
});

// --- Rute Callback Midtrans (Tidak Membutuhkan Autentikasi) ---
Route::get('/payment/success/{invoice_number}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/failed/{invoice_number}', [PaymentController::class, 'paymentFailed'])->name('payment.failed');
Route::get('/payment/pending/{invoice_number}', [PaymentController::class, 'paymentPending'])->name('payment.pending');

// --- Rute API (Membutuhkan Autentikasi Web/Sesi) ---
Route::middleware(['auth:web'])->group(function () {
    Route::post('/api/create-qris-transaction', [PaymentController::class, 'createTransaction']);
    Route::get('/api/services', function () {
        return response()->json(Service::where('is_active', true)->get());
    });
    Route::get('/api/transaction-status/{invoice_number}', [App\Http\Controllers\PaymentController::class, 'getTransactionStatus']);
});

require __DIR__ . '/auth.php';
