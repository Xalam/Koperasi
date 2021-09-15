<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt="Primkop Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Primkop Polbes Smg</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-flat nav-child-indent flex-column" data-widget="treeview"
                role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->is('simpan-pinjam/dashboard*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('simpan-pinjam/master*') ? ' menu-open' : '' }}">
                    @if (auth()->user()->role != 'bendahara_pusat')
                        <a href="#" class="nav-link {{ request()->is('simpan-pinjam/master*') ? ' active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Master
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                    @endif
                    <ul class="nav nav-treeview">
                        @if (auth()->user()->role == 'bendahara' || auth()->user()->role == 'ketua_koperasi' || auth()->user()->role == 'admin')
                            <li class="nav-item">
                                <a href="{{ route('akun.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/master/akun*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Akun</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->role == 'simpan_pinjam' || auth()->user()->role == 'ketua_koperasi' || auth()->user()->role == 'admin')
                            <li class="nav-item">
                                <a href="{{ route('anggota.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/master/anggota*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Anggota</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'ketua_koperasi')
                            <li class="nav-item">
                                <a href="{{ route('admin.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/master/admin*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Administrator</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'ketua_koperasi')
                            <li class="nav-item">
                                <a href="{{ route('instansi.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/master/instansi*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Instansi</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @if (auth()->user()->role == 'simpan_pinjam' || auth()->user()->role == 'ketua_koperasi' || auth()->user()->role == 'admin' || auth()->user()->role == 'bendahara')
                    <li class="nav-item {{ request()->is('simpan-pinjam/simpanan*') ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('simpan-pinjam/simpanan*') ? ' active' : '' }}">
                            <i class="nav-icon fas fa-wallet"></i>
                            <p>
                                Simpanan
                                <i class="right fas fa-angle-left"></i>
                                <span class="right badge badge-danger" id="pulsate-simpanan" hidden>New!</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('data.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/simpanan/data*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Simpanan</p>
                                    <span class="right pulsate-child" id="pulsate-child-simpanan" hidden>&nbsp;</span>
                                </a>
                            </li>
                            @if (auth()->user()->role != 'bendahara')
                                <li class="nav-item">
                                    <a href="{{ route('saldo.index') }}"
                                        class="nav-link {{ request()->is('simpan-pinjam/simpanan/saldo*') ? ' active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Saldo Simpanan</p>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('tarik-saldo.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/simpanan/tarik-saldo*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Permintaan Penarikan</p>
                                    <span class="right pulsate-child" id="pulsate-child-penarikan" hidden>&nbsp;</span>
                                </a>
                            </li>
                            @if (auth()->user()->role != 'bendahara')
                                <li class="nav-item">
                                    <a href="{{ route('tarik-saldo.history') }}"
                                        class="nav-link {{ request()->is('simpan-pinjam/simpanan/riwayat*') ? ' active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Riwayat Penarikan</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->role == 'simpan_pinjam' || auth()->user()->role == 'ketua_koperasi' || auth()->user()->role == 'admin' || auth()->user()->role == 'bendahara')
                    <li class="nav-item {{ request()->is('simpan-pinjam/pinjaman*') ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('simpan-pinjam/pinjaman*') ? ' active' : '' }}">
                            <i class="nav-icon fas fa-hand-holding-usd"></i>
                            <p>
                                Pinjaman
                                <i class="right fas fa-angle-left"></i>
                                <span class="right badge badge-danger" id="pulsate" hidden>New!</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('pengajuan.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/pinjaman/pengajuan*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengajuan Pinjaman</p>
                                    <span class="right pulsate-child" id="pulsate-child-pengajuan" hidden>&nbsp;</span>
                                </a>
                            </li>
                            @if (auth()->user()->role != 'bendahara')
                                <li class="nav-item">
                                    <a href="{{ route('angsuran.index') }}"
                                        class="nav-link {{ request()->is('simpan-pinjam/pinjaman/angsuran*') ? ' active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Angsuran</p>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('tempo.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/pinjaman/tempo*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <marquee direction="left"><span>Pelunasan Sebelum Jatuh Tempo</span></marquee>
                                    <span class="right pulsate-child" id="pulsate-child-pelunasan" hidden>&nbsp;</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="nav-item {{ request()->is('simpan-pinjam/laporan*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('simpan-pinjam/laporan*') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Laporan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (auth()->user()->role != 'simpan_pinjam' && auth()->user()->role != 'bendahara_pusat')
                            <li class="nav-item">
                                <a href="{{ route('jurnal.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/laporan/jurnal*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Jurnal Umum</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->role != 'bendahara_pusat' && auth()->user()->role != 'simpan_pinjam')
                            <li class="nav-item">
                                <a href="{{ route('buku-besar.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/laporan/buku-besar*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Buku Besar</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('lap-simpanan.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/laporan/simpanan*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Simpanan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('lap-pinjaman.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/laporan/pinjaman*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pinjaman</p>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('data-anggota.index') }}"
                                class="nav-link {{ request()->is('simpan-pinjam/laporan/anggota*') ? ' active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Anggota</p>
                            </a>
                        </li>
                        @if (auth()->user()->role != 'bendahara_pusat')
                            <li class="nav-item">
                                <a href="{{ route('shu.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/laporan/shu*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Sisa Hasil Usaha</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ekuitas.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/laporan/ekuitas*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Perubahan Ekuitas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('keuangan.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/laporan/keuangan*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Posisi Keuangan</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->role != 'simpan_pinjam')
                            <li class="nav-item">
                                <a href="{{ route('bendahara.index') }}"
                                    class="nav-link {{ request()->is('simpan-pinjam/laporan/bendahara*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Bendahara Pusat</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @if (auth()->user()->role == 'admin' || auth()->user()->role == 'ketua_koperasi')
                    <li class="nav-item {{ request()->is('simpan-pinjam/pengaturan*') ? ' menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('simpan-pinjam/pengaturan*') ? ' active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Pengaturan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('list.index') }}"
                                    class="nav-link nav-link {{ request()->is('simpan-pinjam/pengaturan/list*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Daftar Pengaturan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pembagian.index') }}"
                                    class="nav-link nav-link {{ request()->is('simpan-pinjam/pengaturan/pembagian*') ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pembagian SHU</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
