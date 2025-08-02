@php
    $url = explode('/', request()->url());
    $page_slug = $url[count($url) - 2];
@endphp

<aside id="sidebar"
    class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width"
    aria-label="Sidebar">
    @if (Auth::user()->role === 'Admin')
        @include('example.layouts.partials.sidebar.admin')
    @elseif(Auth::user()->role === 'Manajer gudang')
        @include('example.layouts.partials.sidebar.manajer')
    @else
        @include('example.layouts.partials.sidebar.staff')
    @endif
</aside>
