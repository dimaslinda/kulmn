<?php

namespace App\Filament\Widgets;

use App\Models\Transaction; // Jangan lupa import model Transaksi Anda
use Carbon\Carbon; // Untuk bekerja dengan tanggal dan waktu
use Filament\Widgets\ChartWidget;

class PeakHoursChart extends ChartWidget
{
    protected static ?string $heading = 'Jam Tertinggi Potong Rambut';

    // Anda bisa mengatur rentang waktu di sini (misalnya untuk filter di UI)
    public ?string $filter = 'today'; // Default filter: Hari ini

    protected static ?int $sort = 5;

    // Metode untuk mengambil data chart
    protected function getData(): array
    {
        $data = $this->getChartDataForFilter($this->filter);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pelanggan',
                    'data' => array_values($data), // Ambil hanya nilai jumlah pelanggan
                    'fill' => 'origin', // Mengisi area di bawah garis
                    'borderColor' => '#3B82F6', // Warna garis (biru Filament)
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)', // Warna area (biru transparan)
                    'tension' => 0.4, // Untuk membuat garis lebih melengkung (smooth)
                ],
            ],
            'labels' => array_keys($data), // Ambil jam sebagai label
        ];
    }

    // Metode pembantu untuk mengambil data berdasarkan filter
    protected function getChartDataForFilter(string $filter): array
    {
        $start = null;
        $end = null;

        switch ($filter) {
            case 'today':
                $start = Carbon::now()->startOfDay();
                $end = Carbon::now()->endOfDay();
                break;
            case 'yesterday':
                $start = Carbon::yesterday()->startOfDay();
                $end = Carbon::yesterday()->endOfDay();
                break;
            case 'this_week':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            default:
                $start = Carbon::now()->startOfDay();
                $end = Carbon::now()->endOfDay();
        }

        // Inisialisasi array untuk semua jam yang mungkin dalam rentang (misal 24 jam)
        $hoursData = [];
        // Asumsi kita hanya tertarik pada jam operasional dari 11:00 sampai 22:00 seperti gambar
        for ($hour = 11; $hour <= 22; $hour++) {
            $hoursData[sprintf('%02d:00', $hour)] = 0;
        }


        // Ambil transaksi dalam rentang waktu yang dipilih
        // Grouping berdasarkan jam dari kolom `created_at` atau `transaction_time`
        $transactions = Transaction::selectRaw('HOUR(created_at) as hour, COUNT(*) as count') // Sesuaikan 'created_at' jika nama kolom waktu Anda berbeda
            ->where('payment_status', 'success')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Isi data yang diambil ke array hoursData
        foreach ($transactions as $transaction) {
            $formattedHour = sprintf('%02d:00', $transaction->hour);
            if (isset($hoursData[$formattedHour])) { // Pastikan jam ada di rentang yang kita inginkan (11-22)
                $hoursData[$formattedHour] = $transaction->count;
            }
        }

        return $hoursData;
    }


    // Metode untuk jenis chart
    protected function getType(): string
    {
        return 'line'; // Menggunakan Line Chart
    }

    // Metode untuk opsi chart (sumbu, tooltip, dll.)
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0, // Pastikan sumbu Y adalah angka bulat
                        'callback' => 'function(value) { return value + " Pelanggan"; }', // Label sumbu Y
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false, // Sembunyikan grid vertikal
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true, // Menampilkan legenda 'Jumlah Pelanggan'
                ],
            ],
            'maintainAspectRatio' => false, // Izinkan chart untuk tidak mempertahankan rasio aspek default
        ];
    }

    // Metode untuk filter (opsional, tapi bagus untuk chart ini)
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'yesterday' => 'Kemarin',
            'this_week' => 'Minggu Ini',
            'this_month' => 'Bulan Ini',
        ];
    }
}
