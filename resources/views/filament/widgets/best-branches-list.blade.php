{{-- resources/views/filament/widgets/best-branches-list.blade.php --}}

<x-filament-widgets::widget>
    <x-filament::card>
        <div class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
            {{ $this->getHeading() }}
        </div>

        <div class="space-y-4">
            @foreach ($this->bestBranches as $branch)
                <div
                    class="flex items-center space-x-4 p-4 rounded-xl bg-white dark:bg-gray-800 shadow border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    {{-- Peringkat dengan emoji dan warna --}}
                    <div
                        class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full text-xl font-bold
                        {{ $branch->rank == 1 ? 'bg-yellow-400 text-white' : ($branch->rank == 2 ? 'bg-gray-300 text-gray-800' : ($branch->rank == 3 ? 'bg-amber-700 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-white')) }}">
                        @if ($branch->rank == 1)
                            🥇
                        @elseif ($branch->rank == 2)
                            🥈
                        @elseif ($branch->rank == 3)
                            🥉
                        @else
                            {{ $branch->rank }}
                        @endif
                    </div>

                    {{-- Nama Cabang dan Pendapatan --}}
                    <div class="flex-grow">
                        <div class="text-base font-semibold text-gray-900 dark:text-white">
                            Cabang {{ $branch->name }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Pendapatan bulan ini
                        </div>
                        <div class="text-sm font-medium text-primary-700 dark:text-primary-400">
                            {{ 'Rp ' . number_format($branch->revenue, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- Persentase Kenaikan/Turunan --}}
                    <div class="flex-shrink-0 text-right text-base font-semibold flex flex-col items-end">
                        <span
                            class="flex items-center {{ floatval($branch->percentage) > 0 ? 'text-green-600 dark:text-green-400' : (floatval($branch->percentage) < 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400') }}">
                            @if (floatval($branch->percentage) > 0)
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 10l5-5 5 5H5z" />
                                </svg>
                                +{{ number_format($branch->percentage, 1) }}%
                            @elseif (floatval($branch->percentage) < 0)
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M15 10l-5 5-5-5h10z" />
                                </svg>
                                {{ number_format($branch->percentage, 1) }}%
                            @else
                                0.0%
                            @endif
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- "Lihat Semua Cabang" Button --}}
        <div class="mt-6 text-center">
            <a href="http://kulmn.test/secret/branches"
                class="text-primary-600 hover:text-primary-500 text-sm font-semibold">
                Lihat Semua Cabang &gt;
            </a>
        </div>
    </x-filament::card>
</x-filament-widgets::widget>
