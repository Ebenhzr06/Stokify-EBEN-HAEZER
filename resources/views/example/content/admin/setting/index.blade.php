@extends('example.layouts.default.dashboard')

@section('content')
    <div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
        <div class="mb-4 col-span-full xl:mb-2">
            <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('Admin.index') }}" class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                            <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                            Home
                        </a>
                    </li>
                   
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Settings</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Application Settings</h1>
        </div>

        {{-- Start of Settings Section --}}
        <div class="col-span-full">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

                {{-- Logo Section --}}
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-4 text-xl font-semibold dark:text-white">Application Logo</h3>

                    @if ($appLogoUrl)
                        <img class="mb-4 rounded-lg w-28 h-28 object-contain" src="{{ $appLogoUrl }}" alt="App Logo">
                    @endif

                    <div class="flex items-center space-x-4">
                        {{-- Upload Form --}}
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 cursor-pointer">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
                                    <path d="M9 13h2v5a1 1 0 11-2 0v-5z"/>
                                </svg>
                                Upload Logo
                                <input type="file" name="app_logo" class="hidden" onchange="this.form.submit()">
                            </label>
                        </form>


                    </div>
                </div>

                {{-- Application Name Section --}}
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-4 text-xl font-semibold dark:text-white">Application Name</h3>
                    <form action="{{ route('settings.app_name.update') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="app_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Application Name</label>
                            <input type="text" name="app_name" id="app_name"
                                class="block w-full p-2.5 border rounded-lg text-sm bg-gray-50 border-gray-300 text-gray-900 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                value="{{ old('app_name', $appName) }}" placeholder="Enter application name">
                        </div>
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Save Settings
                        </button>
                    </form>
                </div>

            </div>
        </div>
        {{-- End of Settings Section --}}
    </div>
@endsection
