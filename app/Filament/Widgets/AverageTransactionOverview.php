<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AverageTransactionOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';


    protected function getStats(): array
    {
        // Hitung rata-rata nilai transaksi
        $averageTransaction = Transaction::avg('total_amount'); // Asumsikan 'amount' adalah kolom nilai transaksi

        // Dapatkan awal dan akhir bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Hitung pendapatan per cabang untuk bulan ini
        $branchRevenues = Transaction::selectRaw('branch_id, SUM(total_amount) as total_revenue')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('branch_id')
            ->orderByDesc('total_revenue')
            ->first(); // Ambil cabang dengan pendapatan tertinggi

        $bestBranchName = 'N/A';
        $bestBranchRevenue = 0;

        if ($branchRevenues) {
            $bestBranch = Branch::find($branchRevenues->branch_id);
            if ($bestBranch) {
                $bestBranchName = $bestBranch->name; // Asumsikan 'name' adalah kolom nama cabang
            }
            $bestBranchRevenue = $branchRevenues->total_revenue;
        }

        // Hitung total transaksi
        $totalTransactions = Transaction::where('payment_status', 'success')->count();
        // Hitung total pendapatan dari semua transaksi
        $totalRevenue = Transaction::sum('total_amount'); // Asumsikan 'amount' adalah kolom pendapatan

        return [
            Stat::make('Total Pendapatan', number_format($totalRevenue, 0, ',', '.'))
                ->description('Pendapatan kumulatif semua cabang')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Transaksi', number_format($totalTransactions, 0, ',', '.'))
                ->description('Jumlah seluruh transaksi semua cabang')
                ->descriptionIcon('heroicon-m-receipt-refund')
                ->color('info'),

            Stat::make('Rata-rata Transaksi', number_format($averageTransaction, 0, ',', '.'))
                ->description('Rata-rata nilai transaksi semua cabang')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('warning'),

            Stat::make('Cabang Terbaik (Bulan Ini)', $bestBranchName)
                ->description('Pendapatan: ' . number_format($bestBranchRevenue, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),
        ];
    }
}
