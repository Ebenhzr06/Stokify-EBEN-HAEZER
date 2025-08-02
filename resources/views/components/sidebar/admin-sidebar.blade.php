<x-sidebar-dashboard>
    <div class="flex items-center">
        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
        <x-sidebar-menu-dashboard routeName="index-practice" title="Dashboard"/ >
    </div>

    <x-sidebar-menu-dropdown-dashboard routeName="practice.*" title="Produk">
        <x-sidebar-menu-dropdown-item-dashboard routeName="practice.first" title="Daftar Produk"/>
        <x-sidebar-menu-dropdown-item-dashboard routeName="practice.second" title="Kategori"/>
        <x-sidebar-menu-dropdown-item-dashboard routeName="practice.third" title="Atribut"/>
        <x-sidebar-menu-dropdown-item-dashboard routeName="practice.four" title="Import/Export"/>
    </x-sidebar-menu-dropdown-dashboard>

    {{-- <x-sidebar-menu-dropdown-dashboard routeName="stock.*" title="Stok">
        <x-sidebar-menu-dropdown-item-dashboard routeName="stock.BarangM" title="Barang Masuk"/>
        <x-sidebar-menu-dropdown-item-dashboard routeName="stock.BarangK" title="Barang Keluar"/>
        <x-sidebar-menu-dropdown-item-dashboard routeName="stock.Opname" title="Opname"/>
    </x-sidebar-menu-dropdown-dashboard> --}}

     <x-sidebar-menu-dashboard routeName="Supplier" title="Supplier"/>

     <x-sidebar-menu-dashboard routeName="Pengguna" title="Pengguna"/>

     <x-sidebar-menu-dashboard routeName="Laporan" title="Laporan"/>

     <x-sidebar-menu-dashboard routeName="Pengaturan" title="Pengaturan"/>
</x-sidebar-dashboard>
