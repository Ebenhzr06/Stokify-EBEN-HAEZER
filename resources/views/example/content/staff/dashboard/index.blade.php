@extends('example.layouts.default.dashboard')

@section('content')
    <div class="px-4 ">

        {{-- Bagian Card Statistik Utama --}}
        <div class="grid w-full grid-cols-1 gap-4 mt-4 xl:grid-cols-2 2xl:grid-cols-2">
            {{-- Card Jumlah Produk --}}

            {{-- Card Jumlah Masuk --}}
            <div
                class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="w-full">
                    <h3 class="text-base font-normal text-gray-500 dark:text-gray-400">Barang masuk Yang perlu di periksa
                    </h3>
                    <span
                        class="text-2xl font-bold leading-none text-gray-900 sm:text-3xl dark:text-white">{{ $productsInCount }}</span>
                    <p class="flex items-center text-base font-normal text-gray-500 dark:text-gray-400">
                    </p>
                </div>
            </div>
            <div
                class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="w-full">
                    <h3 class="text-base font-normal text-gray-500 dark:text-gray-400">Barang keluar yang perlu di periksa
                    </h3>
                    <span
                        class="text-2xl font-bold leading-none text-gray-900 sm:text-3xl dark:text-white">{{ $productsOutCount }}</span>
                    <p class="flex items-center text-base font-normal text-gray-500 dark:text-gray-400">
                </div>
            </div>
        </div>
    @endsection
