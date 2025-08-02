@extends('example.layouts.default.dashboard')
@section('content')
<div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
        <div class="mb-4 col-span-full xl:mb-2">
            <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('Admin.index') }}"
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
                            <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500">Laporan Stok</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Daftar Laporan Stok Barang</h1>
        </div>
    </div>


    <div class="px-4 pt-6">

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No.
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tanggal Data Dibuat
                        </th>
                        {{-- <th scope="col" class="px-6 py-3">
                            SKU
                        </th> --}}
                        <th scope="col" class="px-6 py-3">
                            SKU - Produk
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tipe
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jumlah
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            User
                        </th>
                        {{-- <th scope="col" class="px-6 py-3">
                            Aksi
                        </th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stocks as $key => $stock)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $key + 1 }} {{-- Nomor urut --}}
                            </th>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($stock->date)->format('d-m-Y') }} {{-- Format tanggal --}}
                            </td>
                            {{-- <td class="px-6 py-4">
                                {{ $stock->product->sku ?? 'N/A' }} {{-- Mengambil SKU dari relasi produk
                            </td> --}}
                            <td class="px-6 py-4">
                                {{ $stock->product->sku ?? 'N/A' }} - {{ $stock->product->name ?? 'N/A' }} {{-- Mengambil Nama Produk dari relasi produk --}}
                            </td>
                            @if ($stock->type == 'Masuk')
                            <td class="p-4 whitespace-nowrap">
                                <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-red-100 dark:border-red-400 dark:bg-gray-700 dark:text-red-400">{{ $stock->type }}</span>
                            </td>
                            @else
                            <td class="p-4 whitespace-nowrap">
                                <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 border border-green-100 dark:border-green-500">{{ $stock->type }}</span>
                            </td>
                            @endif
                            <td class="px-6 py-4">
                                {{ $stock->quantity }}
                            </td>
                            @if ($stock->status == 'Ditolak')
                                <td class="p-4 whitespace-nowrap">
                                    <span
                                        class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-red-100 dark:border-red-400 dark:bg-gray-700 dark:text-red-400">{{ $stock->status }}</span>
                                </td>
                            @elseif ($stock->status == 'Diterima')
                                <td class="p-4 whitespace-nowrap">
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 border border-green-100 dark:border-green-500">{{ $stock->status }}</span>
                                </td>
                            @elseif (($stock->status == 'Pending'))
                            <td class="p-4 whitespace-nowrap">
                                    <span
                                        class="bg-orange-100 text-orange-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-orange-100 dark:bg-gray-700 dark:border-orange-300 dark:text-orange-300">{{ $stock->status }}</span>
                                </td>
                            @else
                             <td class="p-4 whitespace-nowrap">
                                    <span
                                        class="bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-purple-100 dark:bg-gray-700 dark:border-purple-500 dark:text-purple-400"">{{ $stock->status }}</span>
                                </td>
                            @endif
                            <td class="px-6 py-4">
                                {{ $stock->user->name }}
                            </td>
                            {{-- <td class="px-6 py-4">
                                {{ $stock->user->name ?? 'N/A' }}
                            </td> --}}
                            {{-- <td class="p-4 space-x-2 whitespace-nowrap">
                                <a href="{{ route('stock.tambah.edit', $stock->id) }}">
                                    <button type="button" id="updateProductButton"
                                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                                        <x-heroicon-s-pencil-square class="w-5 h-5 text-white" />
                                    </button>
                                </a>

                                <form action="{{ route('stock.tambah.hapus', $stock->id) }}" method="POST"
                                    style="display: inline"
                                    onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-800 dark:focus:ring-red-800">
                                        <x-heroicon-s-trash class="w-5 h-5 text-white" />
                                    </button>
                                </form>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Belum ada transaksi stok yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
