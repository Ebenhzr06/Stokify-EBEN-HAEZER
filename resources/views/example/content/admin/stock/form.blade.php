@extends('example.layouts.default.dashboard')
@section('content')
    <div class="px-4 pt-6">
        {{-- Pesan Sukses/Error --}}
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ isset($stock) ? route('stock.update', $stock->id) : route('stock.tambah.simpan') }}"
            method="POST">
            @csrf
            {{-- Jika ini form edit, gunakan metode POST karena route update Anda POST --}}
            {{-- Jika Anda mengubah route update menjadi PUT/PATCH, ganti ini menjadi @method('PUT') atau @method('PATCH') --}}
            @isset($stock)
                @method('PUT')
            @endisset

            <div class="space-y-4">
                <div>
                    <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Transaksi</label>
                    <input type="date" name="date" id="date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required value="{{ old('date', $stock->date ?? \Carbon\Carbon::now()->format('Y-m-d')) }}">
                    @error('date')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field: Product (SKU & Product Name) --}}
                <div>
                    <label for="product_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produk</label>
                    <select name="product_id" id="product_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required>
                        <option value="" class="text-gray-900" selected disabled hidden>--- Pilih Produk ---</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ old('product_id', $stock->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                {{ $product->sku }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field: User (User yang membuat transaksi) --}}
                <div>
                    <label for="user_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dibuat Oleh</label>
                    <select name="user_id" id="user_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required>
                        <option value="" class="text-gray-900" selected disabled hidden>--- Pilih User ---</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id', $stock->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field: Type (Keluar / Masuk) --}}
                <div>
                    <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe Transaksi</label>
                    <select name="type" id="type"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required>
                        <option value="" class="text-gray-900" selected disabled hidden>--- Pilih Tipe ---</option>
                        <option value="Masuk" {{ old('type', $stock->type ?? '') == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="Keluar" {{ old('type', $stock->type ?? '') == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field: Quantity --}}
                <div>
                    <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah (Quantity)</label>
                    <input type="number" name="quantity" id="quantity"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Masukkan jumlah" required min="1" value="{{ old('quantity', $stock->quantity ?? '') }}">
                    @error('quantity')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field: Status --}}
                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                    <select name="status" id="status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="" class="text-gray-900" selected disabled hidden>--- Pilih Status ---</option>
                        <option value="Pending" {{ old('status', $stock->status ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Diterima" {{ old('status', $stock->status ?? '') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="Ditolak" {{ old('status', $stock->status ?? '') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="Dikeluarkan" {{ old('status', $stock->status ?? '') == 'Dikeluarkan' ? 'selected' : '' }}>Dikeluarkan</option>
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="minimum_stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Minimum
                        Stock</label>
                    <input type="text" name="minimum_stock" id="minimum_stock"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Type product Category" required=""
                        value="{{ isset($product) ? $product->minimum_stock : '' }}">
                </div>

                {{-- Field: Notes --}}
                {{-- <div>
                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan (Notes)</label>
                    <textarea name="notes" id="notes" rows="4"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Tambahkan catatan tambahan...">{{ old('notes', $stock->notes ?? '') }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div class="bottom-0 left-0 flex w-full p-4 space-x-2 whitespace-nowrap">
                    <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        {{ isset($stock) ? 'Simpan Perubahan' : 'Tambah Transaksi Stok' }}
                    </button>
                    <a href="{{ route('Admin.stock.index')}}">
                        <button type="button" class="inline-flex px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900">
                            Batal
                        </button>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
