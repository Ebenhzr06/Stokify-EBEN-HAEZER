@extends('example.layouts.default.dashboard')
@section('content')
    <div class="px-4 pt-6">

        <form action="{{ isset($product) ? route('product.update', $product->id) : route('product.tambah.simpan') }}" method="POST" enctype="multipart/form-data">@csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div class="space-y-4">

                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name
                        Product</label>
                    <input type="text" name="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Type Name Product" required="" value="{{ isset($product) ? $product->name : '' }}">
                </div>
                <div>
                    <label for="category_id"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                    <select type="text" name="category_id" id="category_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Type product Category" required="">
                        <option class="text-gray-900" value="" selected disabled hidden>--- Choose Category ---
                        </option>
                        @foreach ($categorys as $row)
                            <option value="{{ $row->id }}"
                                {{ isset($product) && $product->category_id == $row->id ? 'selected' : '' }}>
                                {{ $row->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="supplier_id"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier</label>
                    <select type="text" name="supplier_id" id="supplier_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Type product Supplier" required="">
                        <option class="text-gray-900" value="" selected disabled hidden>--- Choose Supplier ---
                        </option>
                        @foreach ($suppliers as $row)
                            <option value="{{ $row->id }}"
                                {{ isset($product) && $product->supplier_id == $row->id ? 'selected' : '' }}>
                                {{ $row->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="description"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <input type="text" name="description" id="description"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Type Description" required=""
                        value="{{ isset($product) ? $product->description : '' }}">
                </div>
                <div>
                    <label for="sku" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stock Keeping
                        Unit
                    </label>
                    <input type="number" name="sku" id="sku"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Stock Keeping Unit" required="" value="{{ isset($product) ? $product->sku : '' }}">

                </div>
                <div>
                    <label for="purchase_price"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Purchase
                        Price</label>
                    <input type="number" name="purchase_price" id="purchase_price"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="$2999" required="" value="{{ isset($product) ? $product->purchase_price : '' }}">
                </div>
                <div>
                    <label for="selling_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selling
                        Price</label>
                    <input type="number" name="selling_price" id="selling_price"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="$2999" required="" value="{{ isset($product) ? $product->selling_price : '' }}">
                </div>

                <div>
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
                    <input type="file" name="image" id="image"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    {{-- Hapus attribute 'value' dari input type="file" karena tidak berfungsi untuk menampilkan nama file --}}

                </div>

                <div>
                    <label for="minimum_stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Minimum
                        Stock</label>
                    <input type="text" name="minimum_stock" id="minimum_stock"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Type product Category" required=""
                        value="{{ isset($product) ? $product->minimum_stock : '' }}">
                </div>

                <div class="bottom-0 left-0 flex  w-full p-4 space-x-2 whitespace-nowrap">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        {{ isset($product) ? 'Save Changes' : 'Add Product' }}
                    </button>
                    <br>
                    <a href="{{ route('Admin.product.index') }}"><button type="button"
                            data-drawer-dismiss="drawer-create-product-default"
                            aria-controls="drawer-create-product-default"
                            class="inline-flex px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900">
                            <svg aria-hidden="true" class="w-5 h-5 -ml-1 sm:mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </button></a>
                </div>
        </form>
    </div>
@endsection
