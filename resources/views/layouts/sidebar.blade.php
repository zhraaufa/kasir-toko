<aside class="main-sidebar sidebar-dark-orange elevation-4" >
    <a href="/" class="brand-link" >
    <!-- Logo -->
        <img src="{{ asset('images/logo.jpeg') }}" alt="logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"> 
                
                <!-- Menu Home -->
                <x-nav-item title="Home" icon="fas fa-home" :routes="['home']" />

                <!-- Menu Transaksi -->
                <x-nav-item 
                    title="Transaksi" 
                    icon="fas fa-cash-register" 
                    :routes="['transaksi.index', 'transaksi.create', 'transaksi.show']" 
                />

                <!-- Menu Laporan -->
                 <x-nav-item title="Laporan" icon="fas fa-print" :routes="['laporan.index']" />


                <!-- Menu Produk -->
                <x-nav-item title="Produk" icon="fas fa-box-open"
                    :routes="['produk.index', 'produk.create', 'produk.edit']" />

                <!-- Menu Stok -->
                <x-nav-item title="Stok" icon="fas fa-pallet"
                    :routes="['stok.index', 'stok.create']" />

                <!-- Menu Pelanggan -->
                @can('admin')
                    <x-nav-item title="Kategori" icon="fas fa-list"
                        :routes="['kategori.index','kategori.create','kategori.edit']" />
                        @endcan
                        
                <x-nav-item 
                    title="Pelanggan" 
                    icon="fas fa-users" 
                    :routes="['pelanggan.index', 'pelanggan.create', 'pelanggan.edit']" />

                {{-- Tambahkan menu lain di bawah sini jika diperlukan --}}

                <!-- Menu Kategori & User hanya untuk admin -->
                @can('admin')
                    <x-nav-item title="User" icon="fas fa-user-tie" 
                        :routes="['user.index', 'user.create', 'user.edit']" />
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>