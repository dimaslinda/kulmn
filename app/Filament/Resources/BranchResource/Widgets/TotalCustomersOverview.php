<?php

namespace App\Filament\Resources\BranchResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User; // Asumsikan 'User' adalah model pelanggan Anda, atau gunakan 'Customer' jika ada

class TotalCustomersOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        // Hitung total pelanggan
        $totalCustomers = User::count(); // Atau App\Models\Customer::count(); jika Anda punya model Customer

        return [
            Stat::make('Total Pelanggan', number_format($totalCustomers, 0, ',', '.'))
                ->description('Jumlah pelanggan semua cabang')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
