<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-box-seam fs-4"></i>
                        <span class="ms-2">Inventaris</span>
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark">
                        <label class="form-check-label"></label>
                    </div>
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>
                
                <li class="sidebar-item {{ request()->routeIs('dashboard') ?  'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-title">Master Data</li>

                <li class="sidebar-item {{ request()->routeIs('kategoris.*') ? 'active' : '' }}">
                    <a href="{{ route('kategoris. index') }}" class='sidebar-link'>
                        <i class="bi bi-folder-fill"></i>
                        <span>Kategori</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('barangs.*') ?  'active' : '' }}">
                    <a href="{{ route('barangs.index') }}" class='sidebar-link'>
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Barang</span>
                    </a>
                </li>

                <li class="sidebar-title">Transaksi</li>

                <li class="sidebar-item {{ request()->routeIs('transaksis.*') ? 'active' : '' }}">
                    <a href="{{ route('transaksis.index') }}" class='sidebar-link'>
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Transaksi</span>
                    </a>
                </li>

                <li class="sidebar-title">Pengaturan</li>

                <li class="sidebar-item">
                    <a href="{{ route('profile.edit') }}" class='sidebar-link'>
                        <i class="bi bi-person-circle"></i>
                        <span>Profile</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" class='sidebar-link' 
                           onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="bi bi-box-arrow-left"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</div>