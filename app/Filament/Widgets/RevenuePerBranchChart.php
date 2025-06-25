<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget; // Pastikan ini diimpor
use App\Models\Branch;
use App\Models\Transaction;

class RevenuePerBranchChart extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan Per Cabang'; // Judul widget

    // Anda bisa menyesuaikan tinggi chart jika perlu
    // protected static ?int $contentHeight = 300;

    protected function getType(): string
    {
        return 'bar'; // Kita akan menggunakan Bar Chart
    }

    protected function getData(): array
    {
        // Ambil semua cabang
        $branches = Branch::all();
        $labels = [];
        $revenues = [];
        foreach ($branches as $branch) {
            $labels[] = $branch->name;
            $total = $branch->transactions()
                ->where('payment_status', 'success')
                ->sum('total_amount');
            $revenues[] = $total / 1000000; // Dalam juta rupiah
        }
        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (dalam Juta Rp)',
                    'data' => $revenues,
                    // Anda bisa menambahkan warna khusus di sini jika mau
                    // 'backgroundColor' => '#60A5FA', // Warna biru Filament
                    // 'borderColor' => '#3B82F6',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return value + " Jt Rp"; }', // Label sumbu Y
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true, // Menampilkan legenda (label dataset)
                ],
            ],
        ];
    }
}
