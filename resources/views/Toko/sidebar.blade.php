<a id="show-sidebar" class="btn btn-sm" style="z-index: 100;" href="#">
    <i class="fas fa-bars" aria-hidden="true"></i>
</a>
<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
        <div class="sidebar-brand">
            <a class="font-3 text-center mt-2 mb-4"><img src="{{ asset('assets/dist/img/logo-koperasi.png') }}" alt=""
                    width="72px"></a>
            <div id="close-sidebar">
                <i class="fas fa-times" aria-hidden="true"></i>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="/toko/dashboard"><i class="fas fa-th" aria-hidden="true"></i>Dashboard</a>
                </li>
                @if (auth()->user()->jabatan == 'Super_Admin' || auth()->user()->jabatan == 'Kanit')
                <li class="sidebar-dropdown">
                    <a><i class="fas fa-server" aria-hidden="true"></i>Master</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="/toko/master/admin">Admin</a></li>
                            <li><a href="/toko/master/akun">Akun</a></li>
                            <li><a href="/toko/master/barang">Barang</a></li>
                            <li><a href="/toko/master/anggota">Anggota</a></li>
                            <li><a href="/toko/master/supplier">Supplier</a></li>
                        </ul>
                    </div>
                </li>
                @endif
                @if (auth()->user()->jabatan == 'Ketua_Koperasi')
                <li class="sidebar-dropdown">
                    <a><i class="fas fa-server" aria-hidden="true"></i>Master</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="/toko/master/barang">Barang</a></li>
                            <li><a href="/toko/master/anggota">Anggota</a></li>
                            <li><a href="/toko/master/supplier">Supplier</a></li>
                        </ul>
                    </div>
                </li>
                @endif
                @if (auth()->user()->jabatan == 'Gudang')
                <li class="sidebar-dropdown">
                    <a><i class="fas fa-server" aria-hidden="true"></i>Master</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="/toko/master/barang">Barang</a></li>
                            <li><a href="/toko/master/supplier">Supplier</a></li>
                        </ul>
                    </div>
                </li>
                @endif
                @if (auth()->user()->jabatan == 'Kasir')
                <li class="sidebar-dropdown">
                    <a><i class="fas fa-server" aria-hidden="true"></i>Master</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="/toko/master/anggota">Anggota</a></li>
                            <li><a href="/toko/master/barang">Barang</a></li>
                        </ul>
                    </div>
                </li>
                @endif
                @if (auth()->user()->jabatan == 'Super_Admin' || auth()->user()->jabatan == 'Kanit')
                <li class="sidebar-dropdown">
                    <a><i class="fas fa-receipt" aria-hidden="true"></i>Transaksi</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li class="sidebar-dropdown2">
                                <a>Penjualan</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/transaksi/penjualan">Transaksi Penjualan</a></li>
                                        <li><a href="/toko/transaksi/piutang">Piutang</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="sidebar-dropdown2">
                                <a>Pembelian</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/transaksi/pembelian">Transaksi Pembelian</a></li>
                                        <li><a href="/toko/transaksi/hutang">Utang</a></li>
                                        <li><a href="/toko/transaksi/retur-pembelian">Retur Pembelian</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="sidebar-dropdown2">
                                <a>Konsinyasi</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/transaksi/retur-titip-jual">Pengembalian Titip Jual</a></li>
                                        <li><a href="/toko/transaksi/titip-jual">Titip Jual</a></li>
                                        <li><a href="/toko/transaksi/konsinyasi">Utang Konsinyasi</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li><a href="/toko/transaksi/jurnal">Jurnal</a></li>
                            <li><a href="/toko/transaksi/pesanan-online">Pesanan Online</a></li>
                            <li><a href="/toko/transaksi/persediaan">Persediaan</a></li>
                        </ul>
                    </div>
                </li>
                @endif
                @if (auth()->user()->jabatan == 'Gudang')
                <li class="sidebar-dropdown">
                    <a><i class="fas fa-receipt" aria-hidden="true"></i>Transaksi</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li class="sidebar-dropdown2">
                                <a>Pembelian</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/transaksi/pembelian">Transaksi Pembelian</a></li>
                                        <li><a href="/toko/transaksi/retur-pembelian">Retur Pembelian</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="sidebar-dropdown2">
                                <a>Konsinyasi</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/transaksi/retur-titip-jual">Pengembalian Titip Jual</a></li>
                                        <li><a href="/toko/transaksi/titip-jual">Titip Jual</a></li>
                                        <li><a href="/toko/transaksi/konsinyasi">Utang Konsinyasi</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li><a href="/toko/transaksi/jurnal">Jurnal</a></li>
                            <li><a href="/toko/transaksi/persediaan">Persediaan</a></li>
                            <li><a href="/toko/transaksi/hutang">Utang</a></li>
                        </ul>
                    </div>
                </li>
                @endif
                @if (auth()->user()->jabatan == 'Kasir')
                <li class="sidebar-dropdown">
                    <a><i class="fas fa-receipt" aria-hidden="true"></i>Transaksi</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li class="sidebar-dropdown2">
                                <a>Penjualan</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/transaksi/penjualan">Transaksi Penjualan</a></li>
                                        <li><a href="/toko/transaksi/piutang">Piutang</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li><a href="/toko/transaksi/persediaan">Persediaan</a></li>
                            <li><a href="/toko/transaksi/pesanan-online">Pesanan Online</a></li>
                        </ul>
                    </div>
                </li>
                @endif
                @if (auth()->user()->jabatan == 'Super_Admin' || auth()->user()->jabatan == 'Kanit' || auth()->user()->jabatan == 'Ketua_Koperasi')
                <li class="sidebar-dropdown">
                    <a><i class="fas fa-book" aria-hidden="true"></i>Laporan</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="/toko/laporan/anggota">Anggota</a></li>
                            <li><a href="/toko/laporan/data-master">Data Master</a></li>
                            <li class="sidebar-dropdown2">
                                <a>Penjualan</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/laporan/penjualan">Transaksi Penjualan</a></li>
                                        <li><a href="/toko/laporan/piutang">Piutang</a></li>
                                        <li><a href="/toko/laporan/kas-masuk">Kas Masuk</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="sidebar-dropdown2">
                                <a>Pembelian</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/laporan/pembelian">Transaksi Pembelian</a></li>
                                        <li><a href="/toko/laporan/kas-keluar">Kas Keluar</a></li>
                                        <li><a href="/toko/laporan/retur-pembelian">Retur Pembelian</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="sidebar-dropdown2">
                                <a>Persediaan</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/laporan/persediaan">Persediaan</a></li>
                                        <li><a href="/toko/laporan/persediaan/minimal-persediaan">Persediaan Minimal</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li><a href="/toko/laporan/pendapatan">Laba Rugi Toko</a></li>
                        </ul>
                    </div>
                </li>
                @endif
                @if (auth()->user()->jabatan == 'Gudang')
                <li class="sidebar-dropdown">
                    <a><i class="fas fa-book" aria-hidden="true"></i>Laporan</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li class="sidebar-dropdown2">
                                <a>Pembelian</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/laporan/pembelian">Transaksi Pembelian</a></li>
                                        <li><a href="/toko/laporan/kas-keluar">Kas Keluar</a></li>
                                        <li><a href="/toko/laporan/retur-pembelian">Retur Pembelian</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="sidebar-dropdown2">
                                <a>Persediaan</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/laporan/persediaan">Persediaan</a></li>
                                        <li><a href="/toko/laporan/persediaan/minimal-persediaan">Persediaan Minimal</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                @if (auth()->user()->jabatan == 'Kasir')
                <li class="sidebar-dropdown">
                    <a><i class="fas fa-book" aria-hidden="true"></i>Laporan</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="/toko/laporan/anggota">Anggota</a></li>
                            <li class="sidebar-dropdown2">
                                <a>Penjualan</a>
                                <div class="sidebar-submenu2">
                                    <ul>
                                        <li><a href="/toko/laporan/penjualan">Transaksi Penjualan</a></li>
                                        <li><a href="/toko/laporan/piutang">Piutang</a></li>
                                        <li><a href="/toko/laporan/kas-masuk">Kas Masuk</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>