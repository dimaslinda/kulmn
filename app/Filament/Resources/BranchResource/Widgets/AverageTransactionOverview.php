<?php

namespace App\Filament\Resources\BranchResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;

class AverageTransactionOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';


    protected function getStats(): array
    {
        // Hitung rata-rata nilai transaksi
        $averageTransaction = Transaction::avg('total_amount'); // Asumsikan 'amount' adalah kolom nilai transaksi

        return [
            Stat::make('Rata-rata Transaksi', number_format($averageTransaction, 0, ',', '.'))
                ->description('Rata-rata nilai transaksi semua cabang')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('warning'),
        ];
    }
}
