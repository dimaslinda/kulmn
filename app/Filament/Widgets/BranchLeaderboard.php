<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class BranchLeaderboard extends BaseWidget
{
    protected static ?string $heading = 'Klasemen Cabang';
    protected static ?int $perPage = 10;
    protected static bool $isPaginated = true;
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $currentPeriodData = Transaction::query()
            ->select(
                'branch_id',
                DB::raw('SUM(total_amount) as current_omset'),
                DB::raw('COUNT(*) as current_customers'),
                DB::raw('COUNT(*) as current_transactions')
            )
            ->where('payment_status', 'success')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->groupBy('branch_id');

        $previousPeriodData = Transaction::query()
            ->select(
                'branch_id',
                DB::raw('SUM(total_amount) as previous_omset')
            )
            ->where('payment_status', 'success')
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->groupBy('branch_id');

        return Branch::query()
            ->leftJoinSub($currentPeriodData, 'current_data', function ($join) {
                $join->on('branches.id', '=', 'current_data.branch_id');
            })
            ->leftJoinSub($previousPeriodData, 'previous_data', function ($join) {
                $join->on('branches.id', '=', 'previous_data.branch_id');
            })
            ->select([
                'branches.id',
                'branches.name',
                DB::raw('COALESCE(current_data.current_omset, 0) as omset'),
                DB::raw('COALESCE(current_data.current_customers, 0) as customers'),
                DB::raw('COALESCE(current_data.current_transactions, 0) as total_transactions'),
                DB::raw('COALESCE(previous_data.previous_omset, 0) as previous_omset'),
                DB::raw('CASE WHEN COALESCE(current_data.current_transactions, 0) > 0 THEN COALESCE(current_data.current_omset, 0) / COALESCE(current_data.current_transactions, 0) ELSE 0 END as avg_transaction')
            ])
            ->orderByDesc('omset');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('rank')
                ->label('Peringkat')
                ->getStateUsing(fn($rowLoop) => $rowLoop->iteration)
                ->extraAttributes(function ($record) {
                    if (in_array($record->rank, [1, 2, 3])) {
                        return [
                            'class' => 'bg-primary-500/10 text-primary-600 font-bold px-3 py-1 rounded-full inline-block',
                            'style' => 'width: 30px; text-align: center;'
                        ];
                    }
                    return [];
                })
                ->sortable()
                ->grow(false)
                ->width('auto'),

            TextColumn::make('name')
                ->label('Cabang')
                ->searchable()
                ->sortable(),

            TextColumn::make('omset')
                ->label('Omset')
                ->money('IDR')
                ->sortable(),

            // Kolom Pertumbuhan
            TextColumn::make('growth')
                ->label('Pertumbuhan')
                ->getStateUsing(function ($record) {
                    $currentOmset = (float) $record->omset;
                    $previousOmset = (float) $record->previous_omset;

                    // Tambahkan pengecekan yang lebih ketat untuk nol atau mendekati nol
                    if ($previousOmset == 0.0) { // Gunakan 0.0 untuk perbandingan float
                        return $currentOmset > 0 ? 'Baru Tumbuh' : '0%';
                    }

                    $growth = (($currentOmset - $previousOmset) / $previousOmset) * 100;
                    return number_format($growth, 1) . '%';
                })
                ->color(function ($state, $record) {
                    $currentOmset = (float) $record->omset;
                    $previousOmset = (float) $record->previous_omset;

                    // Tambahkan pengecekan yang lebih ketat untuk nol atau mendekati nol
                    if ($previousOmset == 0.0) { // Gunakan 0.0 untuk perbandingan float
                        return $currentOmset > 0 ? 'success' : 'gray';
                    }

                    $growth = (($currentOmset - $previousOmset) / $previousOmset) * 100;
                    return $growth >= 0 ? 'success' : 'danger';
                })
                ->extraAttributes(['class' => 'font-semibold'])
                ->sortable(),

            TextColumn::make('customers')
                ->label('Pelanggan')
                ->numeric()
                ->sortable(),

            TextColumn::make('avg_transaction')
                ->label('Rata-rata Transaksi')
                ->money('IDR')
                ->sortable(),
        ];
    }
}
