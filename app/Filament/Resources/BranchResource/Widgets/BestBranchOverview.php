<?php

namespace App\Filament\Resources\BranchResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use App\Models\Branch; // Pastikan Anda memiliki model Branch
use Carbon\Carbon;

class BestBranchOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '60s'; // Mungkin lebih jarang untuk data bulanan

    protected function getStats(): array
    {
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

        return [
            Stat::make('Cabang Terbaik (Bulan Ini)', $bestBranchName)
                ->description('Pendapatan: ' . number_format($bestBranchRevenue, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),
        ];
    }
}
