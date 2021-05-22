<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Primkop Polrestabes</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Admin</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-flat nav-child-indent flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('simpan-pinjam/dashboard*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('simpan-pinjam/master*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('simpan-pinjam/master*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Master
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('akun.index') }}" class="nav-link {{ request()->is('simpan-pinjam/master/akun*') ? ' active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Akun</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('anggota.index') }}" class="nav-link {{ request()->is('simpan-pinjam/master/anggota*') ? ' active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Anggota</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/top-nav.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Administrator</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->is('simpan-pinjam/simpanan*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('simpan-pinjam/simpanan*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>
                            Simpanan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('data.index') }}" class="nav-link {{ request()->is('simpan-pinjam/simpanan/data*') ? ' active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Simpanan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('saldo.index') }}" class="nav-link {{ request()->is('simpan-pinjam/simpanan/saldo*') ? ' active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Saldo Simpanan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->is('simpan-pinjam/pinjaman*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('simpan-pinjam/pinjaman*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>
                            Pinjaman
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ request()->is('simpan-pinjam/pinjaman/pokok*') ? ' active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Simpanan Pokok
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="l" class="nav-link {{ request()->is('simpan-pinjam/pinjaman/wajib*') ? ' active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Simpanan Wajib</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link {{ request()->is('simpan-pinjam/pinjaman/sukarela*') ? ' active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Simpanan Sukarela</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link {{ request()->is('simpan-pinjam/pinjaman/daftar*') ? ' active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Simpanan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Laporan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/UI/general.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Simpanan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/icons.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pinjaman</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/icons.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Anggota</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/icons.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sisa Hasil Usaha</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/icons.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Perubahan Ekuitas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/icons.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Posisi Keuangan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Pengaturan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/UI/general.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jenis simpanan</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>