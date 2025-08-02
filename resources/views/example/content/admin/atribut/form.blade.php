@extends('example.layouts.default.dashboard')
@section('content')
    <div class="px-4 pt-6">

        <form action="{{ isset($productatribut) ? route('atribut.update', $productatribut->id) : route('atribut.tambah.simpan') }}"
            method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name Product
                        </label>
                    <select type="text" name="product_id" id="product_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Type product name" required="" >
                        <option value="" class="text-gray-900" selected disabled hidden>--- Chose Product ---</option>
                        @foreach ($products as $row)
                            <option value="{{ $row->id }}"
                                {{ isset ($productatribut) && $productatribut->product_id == $row->id ? 'selected' : '' }}>
                                {{ $row->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Attribute Name
                        </label>
                    <input type="text" name="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Type product name" required="" value="{{ isset($productatribut) ? $productatribut->name : '' }}">
                </div>
                <div>
                    <label for="value"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Attribute Value</label>
                    <input type="text" name="value" id="value"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Type product Category" required=""
                        value="{{ isset($productatribut) ? $productatribut->value : '' }}">
                </div>

                    <div  class="bottom-0 left-0 flex  w-full p-4 space-x-2 whitespace-nowrap">
                        <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                   {{ isset($productatribut) ? 'Save Product' : 'Add Product' }}
                </button>
                <br>
                <a href="{{ route('Admin.atribut.index')}}"><button type="button" data-drawer-dismiss="drawer-create-product-default" aria-controls="drawer-create-product-default" class="inline-flex px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900">
                    <svg aria-hidden="true" class="w-5 h-5 -ml-1 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Cancel
                </button></a>

            </div>

            </form>
        </div>
@endsection
