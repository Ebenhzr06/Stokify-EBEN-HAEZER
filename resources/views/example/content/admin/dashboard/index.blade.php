@extends('example.layouts.default.dashboard')

@section('content')
    <div class="px-4 ">

        {{-- Bagian Card Statistik Utama --}}
        <div class="grid w-full grid-cols-1 gap-4 mt-4 xl:grid-cols-2 2xl:grid-cols-3">
            {{-- Card Jumlah Produk --}}
            <div
                class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="w-full">
                    <h3 class="text-base font-normal text-gray-500 dark:text-gray-400">Jumlah Produk</h3>
                    <span class="text-2xl font-bold leading-none text-gray-900 sm:text-3xl dark:text-white">
                        {{ number_format($totalProduk, 0, ',', '.') }}
                    </span>
                    <p class="flex items-center text-base font-normal text-gray-500 dark:text-gray-400">
                        @if (is_numeric($percentageProduk))
                            <span
                                class="flex items-center mr-1.5 text-sm {{ $percentageProduk >= 0 ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                                @if ($percentageProduk >= 0)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M10 3a.75.75 0 01.75.75v10.638l4.19-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" />
                                    </svg>
                                @endif
                                {{ number_format(abs($percentageProduk), 1) }}%
                            </span>
                        @else
                            <span class="flex items-center mr-1.5 text-sm text-green-500 dark:text-green-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" />
                                </svg>
                                Baru
                            </span>
                        @endif
                    </p>
                </div>
            </div>
            {{-- Card Jumlah Masuk --}}
            <div
                class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="w-full">
                    <h3 class="text-base font-normal text-gray-500 dark:text-gray-400">Jumlah Masuk</h3>
                    <span
                        class="text-2xl font-bold leading-none text-gray-900 sm:text-3xl dark:text-white">{{ number_format($totalStockMasuk, 0, ',', '.') }}</span>
                    <p class="flex items-center text-base font-normal text-gray-500 dark:text-gray-400">
                        <span
                            class="flex items-center mr-1.5 text-sm {{ $percentageMasuk >= 0 ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                            @if ($percentageMasuk >= 0)
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z">
                                    </path>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 3a.75.75 0 01.75.75v10.638l4.19-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z">
                                    </path>
                                </svg>
                            @endif
                            {{ number_format(abs($percentageMasuk), 1) }}%
                        </span>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('Admin.index', ['timeframe' => 'day']) }}"
                            class="px-3 py-1 text-sm font-medium rounded-lg {{ request('timeframe', 'day') == 'day' ? 'bg-primary-500 text-white' : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                            Per Hari
                        </a>
                        <a href="{{ route('Admin.index', ['timeframe' => 'month']) }}"
                            class="px-3 py-1 text-sm font-medium rounded-lg {{ request('timeframe') == 'month' ? 'bg-primary-500 text-white' : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                            Per Bulan
                        </a>
                    </div>
                    </p>
                </div>
            </div>
            <div
                class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="w-full">
                    <h3 class="text-base font-normal text-gray-500 dark:text-gray-400">Jumlah Keluar</h3>
                    <span
                        class="text-2xl font-bold leading-none text-gray-900 sm:text-3xl dark:text-white">{{ number_format($totalStockKeluar, 0, ',', '.') }}</span>
                    <p class="flex items-center text-base font-normal text-gray-500 dark:text-gray-400">
                        <span
                            class="flex items-center mr-1.5 text-sm {{ $percentageKeluar >= 0 ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                            @if ($percentageKeluar >= 0)
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z">
                                    </path>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 3a.75.75 0 01.75.75v10.638l4.19-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z">
                                    </path>
                                </svg>
                            @endif
                            {{ number_format(abs($percentageKeluar), 1) }}%
                        </span>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('Admin.index', ['timeframe' => 'day']) }}"
                            class="px-3 py-1 text-sm font-medium rounded-lg {{ request('timeframe', 'day') == 'day' ? 'bg-primary-500 text-white' : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                            Per Hari
                        </a>
                        <a href="{{ route('Admin.index', ['timeframe' => 'month']) }}"
                            class="px-3 py-1 text-sm font-medium rounded-lg {{ request('timeframe') == 'month' ? 'bg-primary-500 text-white' : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                            Per Bulan
                        </a>
                    </div>
                    </p>
                </div>
            </div>
        </div>

        <br>
        <div
            class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-shrink-0">
                    <span class="text-xl font-bold leading-none text-gray-900 sm:text-2xl dark:text-white">Rp
                        {{ number_format($currentPeriodGrossProfit, 0, ',', '.') }}
                    </span>
                    <h3 class="text-base font-light text-gray-500 dark:text-gray-400">Sales {{ $timeframeText }}</h3>
                </div>
                <div class="flex items-center justify-end flex-1 text-base font-medium text-green-500 dark:text-green-400">
                    {{ number_format(abs($percentageChange), 1) }}%
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            {{-- GRAPICH --}}
            <div id="main-chart" class="w-full"></div>
        </div>
        <br>


        <div
            class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 xl:mb-0">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Aktivitas Pengguna</h3>
                <a href="{{ Route('Admin.laporan.user') }}"
                    class="inline-flex items-center p-2 text-sm font-medium rounded-lg text-primary-700 hover:bg-gray-100 dark:text-primary-500 dark:hover:bg-gray-700">
                    Lihat semua
                </a>
            </div>

            <ol class="relative border-l border-gray-200 dark:border-gray-700">
                @foreach ($activities->take(5) as $activity)
                    <li class="mb-10 ml-4">
                        <div
                            class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-800 dark:bg-gray-700">
                        </div>
                        <time class="mb-1 text-sm font-normal leading-none text-red-400 dark:text-gray-500">
                            {{ $activity->created_at->format('d M Y H:i') }}
                        </time>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $activity->user->name }} -
                            {{ $activity->role }}</h3>
                        <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                            {{ $activity->message }}.</p>
                    </li>
                @endforeach
            </ol>
        </div>

    </div>

    {{-- Pemanggilan library ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- Script kustom Anda yang akan menginisialisasi main-chart, langsung di sini --}}
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {

            // Data chart langsung dari Blade
            const stockMovementData = @json($stockMovementData); // Ini sekarang sudah termasuk 'revenue' bulanan

            if (document.getElementById('main-chart')) {
                const options = {
                    chart: {
                        height: 420,
                        type: 'area',
                        fontFamily: 'Inter, sans-serif',
                        foreColor: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280',
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                            name: 'Barang Masuk',
                            data: stockMovementData.incoming
                        },
                        {
                            name: 'Barang Keluar',
                            data: stockMovementData.outgoing
                        },

                    ],
                    colors: ['#0E9F6E', '#F05252'], // Hijau (Masuk), Merah (Keluar), Oranye (Revenue)
                    xaxis: {
                        categories: stockMovementData.labels, // Tetap menggunakan label bulanan
                        labels: {
                            style: {
                                colors: [document.documentElement.classList.contains('dark') ? '#9CA3AF' :
                                    '#6B7280'
                                ],
                                fontSize: '14px',
                                fontWeight: 500,
                            },
                        },
                        axisBorder: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#F3F4F6'
                        },
                        axisTicks: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#F3F4F6'
                        },
                        crosshairs: {
                            show: true,
                            position: 'back',
                            stroke: {
                                color: document.documentElement.classList.contains('dark') ? '#374151' :
                                    '#F3F4F6',
                                width: 1,
                                dashArray: 10,
                            },
                        },
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: [document.documentElement.classList.contains('dark') ? '#9CA3AF' :
                                    '#6B7280'
                                ],
                                fontSize: '14px',
                                fontWeight: 500,
                            },
                            formatter: function(value) {
                                // Karena ada nilai kuantitas dan uang, formatnya lebih umum
                                // Anda mungkin ingin menggunakan Y-axis ganda untuk lebih jelas
                                return value.toLocaleString(
                                    'id-ID'); // Tanpa "Units" atau "Rp" di sumbu Y utama
                            }
                        },
                    },
                    tooltip: {
                        enabled: true,
                        style: {
                            fontSize: '14px',
                            fontFamily: 'Inter, sans-serif',
                        },
                        y: {
                            formatter: function(value, {
                                seriesIndex
                            }) {
                                if (seriesIndex === 0 || seriesIndex ===
                                    1) { // Untuk Incoming & Outgoing Items
                                    return value.toLocaleString('id-ID') + ' Units';
                                } else if (seriesIndex === 2) { // Untuk Revenue
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                                return value.toLocaleString('id-ID'); // Fallback
                            }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            enabled: true,
                            opacityFrom: document.documentElement.classList.contains('dark') ? 0.2 : 0.45,
                            opacityTo: document.documentElement.classList.contains('dark') ? 0.05 : 0
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    grid: {
                        show: true,
                        borderColor: document.documentElement.classList.contains('dark') ? '#374151' :
                            '#F3F4F6',
                        strokeDashArray: 1,
                        padding: {
                            left: 35,
                            bottom: 15
                        }
                    },
                    markers: {
                        size: 5,
                        strokeColors: '#ffffff',
                        hover: {
                            size: undefined,
                            sizeOffset: 3
                        }
                    },
                    legend: {
                        fontSize: '14px',
                        fontWeight: 500,
                        fontFamily: 'Inter, sans-serif',
                        labels: {
                            colors: [document.documentElement.classList.contains('dark') ? '#9CA3AF' :
                                '#6B7280'
                            ]
                        },
                        itemMargin: {
                            horizontal: 10
                        }
                    },
                    responsive: [{
                        breakpoint: 1024,
                        options: {
                            xaxis: {
                                labels: {
                                    show: false
                                }
                            }
                        }
                    }]
                };

                const mainChart = new ApexCharts(document.getElementById('main-chart'), options);
                mainChart.render();

                document.addEventListener('dark-mode', function() {
                    const isDarkMode = document.documentElement.classList.contains('dark');
                    const newColors = {
                        borderColor: isDarkMode ? '#374151' : '#F3F4F6',
                        labelColor: isDarkMode ? '#9CA3AF' : '#6B7280',
                        opacityFrom: isDarkMode ? 0.2 : 0.45,
                        opacityTo: isDarkMode ? 0.05 : 0
                    };

                    mainChart.updateOptions({
                        chart: {
                            foreColor: newColors.labelColor
                        },
                        grid: {
                            borderColor: newColors.borderColor
                        },
                        xaxis: {
                            labels: {
                                style: {
                                    colors: [newColors.labelColor]
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: [newColors.labelColor]
                                }
                            }
                        },
                        legend: {
                            labels: {
                                colors: [newColors.labelColor]
                            }
                        },
                        fill: {
                            gradient: {
                                opacityFrom: newColors.opacityFrom,
                                opacityTo: newColors.opacityTo
                            }
                        }
                    });
                });
            }
        });
    </script>
@endsection
