<?php

namespace App\Filament\Resources\BranchResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction; // Pastikan Anda memiliki model Transaction

class TotalRevenueOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s'; // Opsional: Atur interval refresh

    protected function getStats(): array
    {
        // Hitung total pendapatan dari semua transaksi
        $totalRevenue = Transaction::sum('total_amount'); // Asumsikan 'amount' adalah kolom pendapatan

        return [
            Stat::make('Total Pendapatan', number_format($totalRevenue, 0, ',', '.'))
                ->description('Pendapatan kumulatif semua cabang')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
