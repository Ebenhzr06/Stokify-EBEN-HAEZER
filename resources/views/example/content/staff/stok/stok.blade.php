@extends('example.layouts.default.dashboard')
@section('content')
    <div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
        {{-- ... (header dan breadcrumb) ... --}}
    </div>

    <div class="px-4 pt-6">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">No.</th>
                        <th scope="col" class="px-6 py-3">Tanggal Data Dibuat</th>
                        <th scope="col" class="px-6 py-3">Produk - Kategori</th>
                        <th scope="col" class="px-6 py-3">Tipe</th>
                        <th scope="col" class="px-6 py-3">Jumlah</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stocks as $key => $stock)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $key + 1 }}
                            </th>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($stock->date)->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $stock->product->name ?? 'N/A' }} -
                                {{ $stock->product->category->name ?? 'N/A' }}
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
                            {{-- ... (bagian Status sudah benar) ... --}}
                            @if ($stock->status == 'Ditolak')
                                <td class="p-4 whitespace-nowrap">
                                    <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-red-100 dark:border-red-400 dark:bg-gray-700 dark:text-red-400">{{ $stock->status }}</span>
                                </td>
                            @elseif ($stock->status == 'Diterima')
                                <td class="p-4 whitespace-nowrap">
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 border border-green-100 dark:border-green-500">{{ $stock->status }}</span>
                                </td>
                            @elseif ($stock->status == 'Pending')
                                <td class="p-4 whitespace-nowrap">
                                    <span class="bg-orange-100 text-orange-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-orange-100 dark:bg-gray-700 dark:border-orange-300 dark:text-orange-300">{{ $stock->status }}</span>
                                </td>
                            @else
                                <td class="p-4 whitespace-nowrap">
                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-purple-100 dark:bg-gray-700 dark:border-purple-500 dark:text-purple-400">{{ $stock->status }}</span>
                                </td>
                            @endif
                            <td class="px-6 py-4">
                                {{-- Ganti link a href dengan form untuk aksi POST --}}
                                @if ($stock->status == 'Pending')
                                    <form action="{{ route('stock.confirm', $stock->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                            class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-green-600 dark:hover:bg-green-800 dark:focus:ring-green-800">
                                            Konfirmasi
                                        </button>
                                    </form>
                                    <form action="{{ route('stock.reject', $stock->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-800 dark:focus:ring-red-800">
                                            Ditolak
                                        </button>
                                    </form>
                                @elseif ($stock->status == 'Diterima')
                                    <form action="{{ route('stock.remove', $stock->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                            class="text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-orange-600 dark:hover:bg-orange-800 dark:focus:ring-orange-800">
                                            Dikeluarkan
                                        </button>
                                    </form>
                                @endif

                            </td>
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
