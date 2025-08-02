@extends('example.layouts.default.dashboard')

@section('content')
    <div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
        <div class="mb-4 col-span-full xl:mb-2">
            <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('Manajer.index') }}"
                            class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('Manajer.product.index') }}"
                                class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-white transition-colors duration-200">Produk</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500">Detail Produk</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="px-4 pt-2">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden transform transition-transform duration-300 hover:scale-105">
            <div class="md:flex p-8">
                <div class="md:w-1/3 flex justify-center items-center p-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="Gambar Produk"
                             class=" object-cover rounded-xl shadow-lg transform transition-transform duration-300 hover:scale-110">
                    @else
                        <img src="https://via.placeholder.com/256/E5E7EB/4B5563?text=Tidak+Ada+Gambar"
                             alt="Gambar Default"
                             class="h-64 w-64 object-cover rounded-xl shadow-lg transform transition-transform duration-300 hover:scale-110">
                    @endif
                </div>

                <div class="md:w-2/3 p-4 md:pl-8">
                    <div class="flex items-center mb-4 space-x-3">
                        <span class="px-3 py-1 bg-primary-100 text-primary-800 text-xs font-semibold rounded-full dark:bg-primary-900 dark:text-primary-300">
                            {{ $product->category->name }}
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Supplier: {{ $product->supplier->name }}</p>
                    </div>

                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2 leading-tight">
                        {{ $product->name }}
                    </h1>

                    <p class="text-md text-gray-700 dark:text-gray-300 mb-6">
                        {{ $product->description }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Harga Jual</p>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                Rp{{ number_format($product->selling_price, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Harga Beli</p>
                            <span class="text-2xl font-bold text-red-600 dark:text-red-400">
                                Rp{{ number_format($product->purchase_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <p class="text-sm text-gray-700 dark:text-gray-300">SKU: <span class="font-semibold">{{ $product->sku }}</span></p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.836 0h.582M3 12H2m12.983 5.324l.166.083a2 2 0 00.741 0l.166-.083a2 2 0 001.071-1.35L19 12h1m-10 4v4m0 0H7m3 0v-4m0 0a2 2 0 110-4m0 4v4"></path>
                            </svg>
                            <p class="text-sm text-gray-700 dark:text-gray-300">Stok Min: <span class="font-semibold">{{ $product->minimum_stock }}</span></p>
                        </div>
                    </div>

                    @if($product->attributes && count($product->attributes))
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Atribut Produk</h3>
                            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nama Atribut</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($product->attributes as $attribute)
                                            <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $attribute->name }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                    {{ $attribute->value }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8">
                        <a href="{{ route('Manajer.product.index') }}"
                           class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gray-800 rounded-lg shadow-md hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-800 transition-all duration-300">
                           <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
