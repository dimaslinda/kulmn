<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget; // Pastikan ini diimpor
use App\Models\Branch;
use App\Models\Transaction;

class RevenuePerBranchChart extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan Per Cabang'; // Judul widget

    protected static ?int $sort = 4;
    // Anda bisa menyesuaikan tinggi chart jika perlu
    // protected static ?int $contentHeight = 300;

    protected function getType(): string
    {
        return 'bar'; // Kita akan menggunakan Bar Chart
    }

    protected function getData(): array
    {
        // Ambil 10 cabang dengan pendapatan terbesar
        $branches = Branch::withSum(['transactions as total_amount' => function ($q) {
            $q->where('payment_status', 'success');
        }], 'total_amount')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();
        $labels = [];
        $revenues = [];
        foreach ($branches as $branch) {
            $labels[] = $branch->name;
            $total = $branch->total_amount ?? 0;
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

    /**
     * Opsi konfigurasi chart yang digunakan.
     *
     * @return array
     */
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
