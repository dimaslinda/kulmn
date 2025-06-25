<?php

namespace App\Filament\Widgets;

use App\Models\Branch; // Jangan lupa import model Branch Anda
use Filament\Widgets\Widget; // Import Base Widget
use Carbon\Carbon;

class BestBranchesList extends Widget
{
    protected static string $view = 'filament.widgets.best-branches-list'; // Path ke file Blade widget ini

    protected static ?string $heading = '3 Cabang Terbaik'; // Judul widget

    protected static ?int $sort = 2;

    // Anda bisa mengatur column span untuk widget ini di dashboard
    // Misalnya, untuk mengambil 1/3 lebar layar besar:
    // Akan mengambil 1 kolom jika di dalam Grid, atau 1/3 jika default Filament Panel
    // Jika Anda ingin ini mengambil 100% lebar (untuk layar kecil)
    // dan kemudian misalnya 1/2 di layar medium dan 1/3 di layar besar:
    // protected int | string | array $columnSpan = ['default' => 'full', 'md' => 1, 'lg' => 1];

    // Anda dapat menambahkan properti untuk menyimpan data cabang
    public $bestBranches;

    public function mount(): void
    {
        // Ambil awal dan akhir bulan ini dan bulan lalu
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // Ambil 3 cabang dengan pendapatan tertinggi bulan ini (hanya transaksi sukses)
        $branches = Branch::select('branches.*')
            ->withSum(['transactions as this_month_revenue' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->where('payment_status', 'success')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }], 'total_amount')
            ->withSum(['transactions as last_month_revenue' => function ($q) use ($startOfLastMonth, $endOfLastMonth) {
                $q->where('payment_status', 'success')
                    ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth]);
            }], 'total_amount')
            ->orderByDesc('this_month_revenue')
            ->limit(3)
            ->get();

        $this->bestBranches = $branches->map(function ($branch, $index) {
            $thisMonth = $branch->this_month_revenue ?? 0;
            $lastMonth = $branch->last_month_revenue ?? 0;
            if ($lastMonth > 0) {
                $percent = (($thisMonth - $lastMonth) / $lastMonth) * 100;
            } else {
                $percent = $thisMonth > 0 ? 100 : 0;
            }
            return (object) [
                'rank' => $index + 1,
                'name' => $branch->name,
                'revenue' => $thisMonth,
                'percentage' => $percent,
            ];
        });
    }

    // Anda tidak perlu metode getStats() atau getListRecords() lagi
    // karena kita langsung mengambil data di mount() dan meneruskannya ke view.

    public static function getHeading(): ?string
    {
        return '3 Cabang Terbaik';
    }
}
