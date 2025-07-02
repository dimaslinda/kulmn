<?php

namespace App\Filament\Widgets;

use App\Models\Transaction; // Jangan lupa import model Transaksi Anda
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class PaymentMethodChart extends ChartWidget
{
    protected static ?string $heading = 'Metode Pembayaran';
    protected static ?int $contentHeight = 300; // Atur tinggi chart, samakan dengan chart lain

    // Properti filter untuk memilih periode waktu (Hari Ini, Minggu Ini, dll.)
    public ?string $filter = 'today'; // Default filter: Hari Ini

    protected static ?int $sort = 3;

    // Metode untuk menentukan jenis chart
    protected function getType(): string
    {
        return 'bar'; // Mengubah menjadi Bar Chart
    }

    // Metode untuk mengambil data chart
    protected function getData(): array
    {
        $start = null;
        $end = null;

        switch ($this->filter) {
            case 'today':
                $start = Carbon::now()->startOfDay();
                $end = Carbon::now()->endOfDay();
                break;
            case 'this_week':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            default: // Default ke Hari Ini jika filter tidak cocok
                $start = Carbon::now()->startOfDay();
                $end = Carbon::now()->endOfDay();
        }

        // Ambil transaksi dalam rentang waktu yang dipilih
        // Grouping berdasarkan payment_method dan hitung jumlahnya
        $paymentMethodsData = Transaction::selectRaw('payment_method, COUNT(*) as count')
            ->where('payment_status', 'success')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('payment_method')
            ->get();

        // Inisialisasi data chart
        $labels = [];
        $data = [];
        $colors = [];

        // Warna yang sesuai dengan gambar: Biru untuk QRIS, Hijau untuk Tunai
        $customColors = [
            'QRIS' => '#60A5FA', // Biru
            'cash' => '#34D399', // Hijau
            // Tambahkan warna untuk metode lain jika ada
        ];

        foreach ($paymentMethodsData as $item) {
            $labels[] = $item->payment_method;
            $data[] = $item->count;
            $colors[] = $customColors[$item->payment_method] ?? '#9CA3AF'; // Fallback warna abu-abu
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi', // Label untuk legend
                    'data' => $data,
                    'backgroundColor' => $colors, // Menggunakan warna kustom
                ],
            ],
            'labels' => $labels,
        ];
    }

    // Metode untuk opsi chart (legenda, tooltip, dll.)
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => false, // Untuk bar chart, biasanya legend tidak diperlukan
                ],
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        // Menampilkan jumlah di tooltip
                        'label' => 'function(context) {
                            return context.label + ": " + context.parsed.y;
                        }'
                    ]
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Transaksi',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Metode Pembayaran',
                    ],
                ],
            ],
        ];
    }

    // Metode untuk filter waktu
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'this_week' => 'Minggu Ini',
            'this_month' => 'Bulan Ini',
        ];
    }
}
