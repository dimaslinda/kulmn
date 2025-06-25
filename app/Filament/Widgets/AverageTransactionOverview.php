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
    protected static ?int $sort = 1;


    protected function getStats(): array
    {
        // Hitung rata-rata nilai transaksi
        $averageTransaction = Transaction::where('payment_status', 'success')->avg('total_amount'); // Asumsikan 'amount' adalah kolom nilai transaksi

        // Dapatkan awal dan akhir bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Hitung pendapatan per cabang untuk bulan ini
        $branchRevenues = Transaction::selectRaw('branch_id, SUM(total_amount) as total_revenue')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('branch_id')
            ->where('payment_status', 'success')
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
        $totalRevenue = Transaction::where('payment_status', 'success')->sum('total_amount'); // Asumsikan 'amount' adalah kolom pendapatan

        // Hitung total pendapatan bulan ini dan bulan lalu
        $thisMonthRevenue = Transaction::where('payment_status', 'success')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $lastMonthRevenue = Transaction::where('payment_status', 'success')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_amount');
        $trendIcon = $thisMonthRevenue >= $lastMonthRevenue ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $trendColor = $thisMonthRevenue >= $lastMonthRevenue ? 'success' : 'danger';

        // Hitung total transaksi bulan ini dan bulan lalu
        $thisMonthTransactions = Transaction::where('payment_status', 'success')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        $lastMonthTransactions = Transaction::where('payment_status', 'success')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();
        $transactionTrendColor = $thisMonthTransactions >= $lastMonthTransactions ? 'success' : 'danger';
        if ($lastMonthTransactions > 0) {
            $transactionPercent = (($thisMonthTransactions - $lastMonthTransactions) / $lastMonthTransactions) * 100;
        } else {
            $transactionPercent = $thisMonthTransactions > 0 ? 100 : 0;
        }
        $transactionPercentHint = ($transactionPercent >= 0 ? '+' : '') . number_format($transactionPercent, 1, ',', '.') . '%';

        // Hitung persentase perubahan pendapatan
        if ($lastMonthRevenue > 0) {
            $revenuePercent = (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        } else {
            $revenuePercent = $thisMonthRevenue > 0 ? 100 : 0;
        }
        $revenuePercentHint = ($revenuePercent >= 0 ? '+' : '') . number_format($revenuePercent, 1, ',', '.') . '%';

        // Hitung rata-rata transaksi bulan ini dan bulan lalu
        $thisMonthAvgTransaction = Transaction::where('payment_status', 'success')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->avg('total_amount');
        $lastMonthAvgTransaction = Transaction::where('payment_status', 'success')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->avg('total_amount');
        $avgTrendIcon = $thisMonthAvgTransaction >= $lastMonthAvgTransaction ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $avgTrendColor = $thisMonthAvgTransaction >= $lastMonthAvgTransaction ? 'success' : 'danger';
        if ($lastMonthAvgTransaction > 0) {
            $avgPercent = (($thisMonthAvgTransaction - $lastMonthAvgTransaction) / $lastMonthAvgTransaction) * 100;
        } else {
            $avgPercent = $thisMonthAvgTransaction > 0 ? 100 : 0;
        }
        $avgPercentHint = ($avgPercent >= 0 ? '+' : '') . number_format($avgPercent, 1, ',', '.') . '%';

        return [
            Stat::make('Total Pendapatan', number_format($totalRevenue, 0, ',', '.'))
                ->description('Perubahan: ' . $revenuePercentHint . ' | Pendapatan kumulatif semua cabang')
                ->descriptionIcon($trendIcon)
                ->color($trendColor),

            Stat::make('Total Transaksi', number_format($totalTransactions, 0, ',', '.'))
                ->description('Perubahan: ' . $transactionPercentHint)
                ->descriptionIcon($trendIcon)
                ->color($transactionTrendColor),

            Stat::make('Rata-rata Transaksi', number_format($averageTransaction, 0, ',', '.'))
                ->description('Perubahan: ' . $avgPercentHint)
                ->descriptionIcon($avgTrendIcon)
                ->color($avgTrendColor),

            Stat::make('Cabang Terbaik (Bulan Ini)', $bestBranchName)
                ->description('Pendapatan: ' . number_format($bestBranchRevenue, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),
        ];
    }
}
