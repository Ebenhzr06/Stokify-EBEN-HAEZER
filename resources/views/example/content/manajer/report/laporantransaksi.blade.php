@extends('example.layouts.default.dashboard')
@section('content')
    <div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
        <div class="mb-4 col-span-full xl:mb-2">
            <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('Manajer.index') }}"
                            class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                            <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <a href="#"
                                class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">Laporan</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500">Laporan Transaksi</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Daftar Laporan Transaksi</h1>
        </div>
    </div>


    <div class="px-4 pt-6">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Barang Masuk</h2>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-8">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">No.</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">SKU - Produk</th>
                            <th class="px-6 py-3">Jumlah</th>
                            <th class="px-6 py-3">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($stocks as $stock)
                            @if ($stock->type == 'Masuk')
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $no++ }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($stock->date)->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4">{{ $stock->product->sku ?? 'N/A' }} -
                                        {{ $stock->product->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $stock->quantity }}</td>
                                    <td class="px-6 py-4">{{ $stock->user->name ?? 'N/A' }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Barang Keluar</h2>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-8">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">No.</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">SKU - Produk</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($stocks as $stock)
                                @if ($stock->type == 'Keluar')
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">{{ $no++ }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($stock->date)->format('d-m-Y') }}
                                        </td>
                                        <td class="px-6 py-4">{{ $stock->product->sku ?? 'N/A' }} -
                                            {{ $stock->product->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $stock->quantity }}</td>
                                        <td class="px-6 py-4">{{ $stock->user->name ?? 'N/A' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
