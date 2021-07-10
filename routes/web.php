<?php

use App\Http\Controllers\Simpan_Pinjam\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Toko\DataAkunController;
use App\Http\Controllers\Toko\DataBarangController;
use App\Http\Controllers\Toko\DataSupplierController;
use App\Http\Controllers\Toko\DataAnggotaController;
use App\Http\Controllers\Toko\NomorTransaksiController;
use App\Http\Controllers\Toko\NomorJurnalController;

use App\Http\Controllers\Toko\Android\AndroidController;
use App\Http\Controllers\Toko\AuthController;
use App\Http\Controllers\Toko\Transaksi\Pembelian\PembelianController;
use App\Http\Controllers\Toko\Transaksi\Penjualan\PenjualanController;
use App\Http\Controllers\Toko\Transaksi\Retur\ReturPembelianController;
use App\Http\Controllers\Toko\Transaksi\Hutang\HutangController;
use App\Http\Controllers\Toko\Transaksi\Piutang\PiutangController;
use App\Http\Controllers\Toko\Transaksi\Jurnal\JurnalController;
use App\Http\Controllers\Toko\Transaksi\TitipJual\TitipJualController;
use App\Http\Controllers\Toko\Transaksi\JurnalUmum\JurnalUmumController;

use App\Http\Controllers\Toko\Master\Barang\BarangController;
use App\Http\Controllers\Toko\Master\Admin\AdminController;
use App\Http\Controllers\Toko\Master\Akun\AkunController;
use App\Http\Controllers\Toko\Master\Anggota\AnggotaController;
use App\Http\Controllers\Toko\Master\Supplier\SupplierController;

use App\Http\Controllers\Toko\Laporan\Akuntansi\LaporanAkuntansiController;
use App\Http\Controllers\Toko\Laporan\KasKeluar\LaporanKasKeluarController;
use App\Http\Controllers\Toko\Laporan\KasMasuk\LaporanKasMasukController;
use App\Http\Controllers\Toko\Laporan\Master\LaporanMasterController;
use App\Http\Controllers\Toko\Laporan\Pembelian\LaporanPembelianController;
use App\Http\Controllers\Toko\Laporan\Pendapatan\LaporanPendapatanController;
use App\Http\Controllers\Toko\Laporan\Penjualan\LaporanPenjualanController;
use App\Http\Controllers\Toko\Laporan\Persediaan\LaporanPersediaanController;
use App\Http\Controllers\Toko\Laporan\Retur\LaporanReturPembelianController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//API
Route::group(['prefix' => 'api'], function () {
    Route::get('/data-akun/{id}', [DataAkunController::class, 'dataAkun']);
    Route::get('/data-barang/{id}', [DataBarangController::class, 'dataBarang']);
    Route::get('/data-retur-barang/{id}', [DataBarangController::class, 'dataReturBarang']);
    Route::get('/data-retur-detail-barang/{nomor}/{id}', [DataBarangController::class, 'dataReturDetailBarang']);
    Route::get('/data-supplier/{id}', [DataSupplierController::class, 'dataSupplier']);
    Route::get('/data-retur-supplier/{id}', [DataSupplierController::class, 'dataReturSupplier']);
    Route::get('/data-hutang-supplier/{id}', [DataSupplierController::class, 'dataHutangSupplier']);
    Route::get('/data-anggota/{id}', [DataAnggotaController::class, 'dataAnggota']);
    Route::get('/nomor-pembelian/{tanggal}', [NomorTransaksiController::class, 'nomorPembelian']);
    Route::get('/nomor-jurnal-pembelian/{tanggal}', [NomorJurnalController::class, 'nomorJurnalPembelian']);
    Route::get('/nomor-retur-pembelian/{tanggal}', [NomorTransaksiController::class, 'nomorReturPembelian']);
    Route::get('/nomor-jurnal-retur-pembelian/{tanggal}', [NomorJurnalController::class, 'nomorJurnalReturPembelian']);
    Route::get('/nomor-penjualan/{tanggal}', [NomorTransaksiController::class, 'nomorPenjualan']);
    Route::get('/nomor-jurnal-penjualan/{tanggal}', [NomorJurnalController::class, 'nomorJurnalPenjualan']);
    Route::get('/nomor-angsuran/{tanggal}', [NomorTransaksiController::class, 'nomorAngsuran']);
    Route::get('/nomor-jurnal-angsuran/{tanggal}', [NomorJurnalController::class, 'nomorJurnalAngsuran']);
    Route::get('/nomor-terima-piutang/{tanggal}', [NomorTransaksiController::class, 'nomorTerimaPiutang']);
    Route::get('/nomor-jurnal-terima-piutang/{tanggal}', [NomorJurnalController::class, 'nomorJurnalTerimaPiutang']);
    Route::get('/nomor-titip-jual/{tanggal}', [NomorTransaksiController::class, 'nomorTitipJual']);
    Route::get('/nomor-jurnal-titip-jual/{tanggal}', [NomorJurnalController::class, 'nomorJurnalTitipJual']);
    Route::get('/nomor-jurnal-umum/{tanggal}', [NomorJurnalController::class, 'nomorJurnalUmum']);
});

Route::get('/', function () {
    return view('index');
})->name('login');

Route::group(['prefix' => 'toko'], function () {
    //Login
    Route::get('/login', [AuthController::class, 'index'])->name('t-login');
    Route::post('/login/store', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register/store', [AuthController::class, 'store']);

    Route::group(['middleware' => 'auth:toko'], function () {
        Route::get('/dashboard', function () {
            return view('welcome');
        });

        Route::group(['middleware' => 'auth:toko'], function () {
            Route::get('/', function () {
                return view('welcome');
            });
        });

        Route::get('/logout', [AuthController::class, 'logout']);

        //Transaksi
        Route::group(['prefix' => 'transaksi'], function () {
            Route::group(['prefix' => 'pembelian'], function () {
                Route::get('/', [PembelianController::class, 'index']);
                Route::post('/store', [PembelianController::class, 'store']);
                Route::post('/beli', [PembelianController::class, 'buy']);
                Route::post('/cancel', [PembelianController::class, 'cancel']);
                Route::get('/{nomor}', [PembelianController::class, 'show']);
                Route::post('/delete/{nomor}', [PembelianController::class, 'delete']);
            });

            Route::group(['prefix' => 'penjualan'], function () {
                Route::get('/', [PenjualanController::class, 'index']);
                Route::post('/store', [PenjualanController::class, 'store']);
                Route::post('/jual', [PenjualanController::class, 'sell']);
                Route::post('/cancel', [PenjualanController::class, 'cancel']);
                Route::get('/{nomor}', [PenjualanController::class, 'show']);
                Route::post('/delete/{nomor}', [PenjualanController::class, 'delete']);
            });

            Route::group(['prefix' => 'retur-pembelian'], function () {
                Route::get('/', [ReturPembelianController::class, 'index']);
                Route::post('/store', [ReturPembelianController::class, 'store']);
                Route::post('/retur', [ReturPembelianController::class, 'retur']);
                Route::post('/cancel', [ReturPembelianController::class, 'cancel']);
                Route::get('/{nomor}', [ReturPembelianController::class, 'show']);
                Route::post('/delete/{nomor}', [ReturPembelianController::class, 'delete']);
            });

            Route::group(['prefix' => 'hutang'], function () {
                Route::get('/', [HutangController::class, 'index']);
                Route::post('/store', [HutangController::class, 'store']);
                Route::post('/cancel', [HutangController::class, 'cancel']);
                Route::get('/{nomor_beli}', [HutangController::class, 'show']);
                Route::post('/delete/{nomor}', [HutangController::class, 'delete']);
            });

            Route::group(['prefix' => 'piutang'], function () {
                Route::get('/', [PiutangController::class, 'index']);
                Route::post('/store', [PiutangController::class, 'store']);
                Route::post('/cancel', [PiutangController::class, 'cancel']);
                Route::get('/{nomor_jual}', [PiutangController::class, 'show']);
                Route::post('/delete/{nomor}', [PiutangController::class, 'delete']);
            });

            Route::group(['prefix' => 'titip-jual'], function () {
                Route::get('/', [TitipJualController::class, 'index']);
                Route::post('/store', [TitipJualController::class, 'store']);
                Route::post('/cancel', [TitipJualController::class, 'cancel']);
                Route::get('/{nomor_jual}', [TitipJualController::class, 'show']);
                Route::post('/delete/{nomor}', [TitipJualController::class, 'delete']);
            });

            Route::group(['prefix' => 'jurnal'], function () {
                Route::get('/', [JurnalController::class, 'index']);
            });

            Route::group(['prefix' => 'jurnal-umum'], function () {
                Route::get('/', [JurnalUmumController::class, 'create']);
                Route::get('/create', [JurnalUmumController::class, 'create']);
                Route::post('/store', [JurnalUmumController::class, 'store']);
                Route::post('/save', [JurnalUmumController::class, 'save']);
                Route::post('/cancel', [JurnalUmumController::class, 'cancel']);
                Route::get('/{nomor_jual}', [JurnalUmumController::class, 'show']);
                Route::post('/delete/{id}', [JurnalUmumController::class, 'delete']);
            });
        });

        //Laporan
        Route::group(['prefix' => 'laporan'], function () {
            Route::group(['prefix' => 'akuntansi'], function () {
                Route::get('/', [LaporanAkuntansiController::class, 'index']);
            });

            Route::group(['prefix' => 'data-master'], function () {
                Route::get('/', [LaporanMasterController::class, 'index']);
                Route::get('/print/{bagian}', [LaporanMasterController::class, 'print']);
                Route::get('/export/{bagian}', [LaporanMasterController::class, 'export']);
            });

            Route::group(['prefix' => 'pembelian'], function () {
                Route::get('/', [LaporanPembelianController::class, 'index']);
                Route::get('/print/{type}/{awal}/{akhir}', [LaporanPembelianController::class, 'print']);
                Route::get('/export/{type}/{awal}/{akhir}', [LaporanPembelianController::class, 'export']);
            });

            Route::group(['prefix' => 'retur-pembelian'], function () {
                Route::get('/', [LaporanReturPembelianController::class, 'index']);
                Route::get('/print/{awal}/{akhir}', [LaporanReturPembelianController::class, 'print']);
                Route::get('/export/{awal}/{akhir}', [LaporanReturPembelianController::class, 'export']);
            });

            Route::group(['prefix' => 'penjualan'], function () {
                Route::get('/', [LaporanPenjualanController::class, 'index']);
                Route::get('/print/{type}/{awal}/{akhir}', [LaporanPenjualanController::class, 'print']);
                Route::get('/export/{type}/{awal}/{akhir}', [LaporanPenjualanController::class, 'export']);
            });

            Route::group(['prefix' => 'persediaan'], function () {
                Route::get('/', [LaporanPersediaanController::class, 'index']);
                Route::get('/print/{stok}', [LaporanPersediaanController::class, 'print']);
                Route::get('/export/{stok}', [LaporanPersediaanController::class, 'export']);
            });

            Route::group(['prefix' => 'kas-masuk'], function () {
                Route::get('/', [LaporanKasMasukController::class, 'index']);
                Route::get('/print/{jenis}/{awal}/{akhir}', [LaporanKasMasukController::class, 'print']);
                Route::get('/export/{jenis}/{awal}/{akhir}', [LaporanKasMasukController::class, 'export']);
            });

            Route::group(['prefix' => 'kas-keluar'], function () {
                Route::get('/', [LaporanKasKeluarController::class, 'index']);
                Route::get('/print/{jenis}/{awal}/{akhir}', [LaporanKasKeluarController::class, 'print']);
                Route::get('/export/{jenis}/{awal}/{akhir}', [LaporanKasKeluarController::class, 'export']);
            });

            Route::group(['prefix' => 'pendapatan'], function () {
                Route::get('/', [LaporanPendapatanController::class, 'index']);
            });
        });

        //Master
        Route::group(['prefix' => 'master'], function () {
            Route::group(['prefix' => 'barang'], function () {
                Route::get('/', [BarangController::class, 'index']);
                Route::get('/create', [BarangController::class, 'create']);
                Route::post('/store', [BarangController::class, 'store']);
                Route::post('/update', [BarangController::class, 'update']);
                Route::post('/delete', [BarangController::class, 'delete']);
                Route::post('/remove-notification/{id}', [BarangController::class, 'removeNotification']);
            });

            Route::group(['prefix' => 'admin'], function () {
                Route::get('/', [AdminController::class, 'index']);
                Route::get('/create', [AdminController::class, 'create']);
                Route::post('/store', [AdminController::class, 'store']);
                Route::post('/update', [AdminController::class, 'update']);
                Route::post('/delete', [AdminController::class, 'delete']);
            });

            Route::group(['prefix' => 'akun'], function () {
                Route::get('/', [AkunController::class, 'index']);
                Route::get('/create', [AkunController::class, 'create']);
                Route::post('/store', [AkunController::class, 'store']);
                Route::post('/update', [AkunController::class, 'update']);
                Route::post('/delete', [AkunController::class, 'delete']);
            });

            Route::group(['prefix' => 'anggota'], function () {
                Route::get('/', [AnggotaController::class, 'index']);
                Route::get('/create', [AnggotaController::class, 'create']);
                Route::post('/store', [AnggotaController::class, 'store']);
                Route::post('/update', [AnggotaController::class, 'update']);
                Route::post('/delete', [AnggotaController::class, 'delete']);
            });

            Route::group(['prefix' => 'supplier'], function () {
                Route::get('/', [SupplierController::class, 'index']);
                Route::get('/create', [SupplierController::class, 'create']);
                Route::post('/store', [SupplierController::class, 'store']);
                Route::post('/update', [SupplierController::class, 'update']);
                Route::post('/delete', [SupplierController::class, 'delete']);
            });
        });
    });
});

//SIMPAN PINJAM
Route::prefix('simpan-pinjam')->group(function () {
    #Login
    Route::get('login', 'Simpan_Pinjam\Auth\LoginController@login')->name('s-login');
    Route::post('postlogin', 'Simpan_Pinjam\Auth\LoginController@post_login')->name('post-login');
    Route::get('logout', 'Simpan_Pinjam\Auth\LoginController@logout')->name('s-logout');

    Route::get('/', function () {
        return redirect()->route('s-login');
    });

    Route::get('/dashboard', function () {
        return redirect()->route('s-login');
    });
});

//checkrole:admin,bendahara,bendahara_pusat,ketua_koperasi,simpan_pinjam
Route::group(['prefix' => 'simpan-pinjam', 'middleware' => ['auth:simpan-pinjam', 'checkrole:admin,bendahara,bendahara_pusat,ketua_koperasi,simpan_pinjam']], function () {

    #Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    #Master
    Route::prefix('master')->group(function () {
        Route::resource('anggota', 'Simpan_Pinjam\Master\Anggota\AnggotaController');
        Route::get('anggota/modal/{id}', 'Simpan_Pinjam\Master\Anggota\AnggotaController@modal')->name('anggota.modal');
        Route::get('anggota/cetak/{id}', 'Simpan_Pinjam\Master\Anggota\AnggotaController@print')->name('anggota.print');

        Route::resource('akun', 'Simpan_Pinjam\Master\Akun\AkunController');
        Route::get('akun/modal/{id}', 'Simpan_Pinjam\Master\Akun\AkunController@modal')->name('akun.modal');

        Route::resource('admin', 'Simpan_Pinjam\Master\User\UserController')->middleware('checkrole:admin');
        Route::get('admin/modal/{id}', 'Simpan_Pinjam\Master\User\UserController@modal')->name('admin.modal');
    });

    #Simpanan
    Route::prefix('simpanan')->group(function () {
        Route::resource('data', 'Simpan_Pinjam\Simpanan\SimpananController');
        Route::get('data/cetak/{id}', 'Simpan_Pinjam\Simpanan\SimpananController@print')->name('data.print');
        Route::get('data/cetak/show/{id}', 'Simpan_Pinjam\Simpanan\SimpananController@print_show')->name('data.print-show');
        Route::get('data/cetak/modal/{id}', 'Simpan_Pinjam\Simpanan\SimpananController@modal')->name('data.modal');
        Route::post('data/store-all', 'Simpan_Pinjam\Simpanan\SimpananController@store_all')->name('data.store-all');
        Route::get('data/konfirmasi/{id}', 'Simpan_Pinjam\Simpanan\SimpananController@konfirmasi')->name('data.konfirmasi');

        Route::resource('saldo', 'Simpan_Pinjam\Simpanan\SaldoController');

        Route::get('riwayat', 'Simpan_Pinjam\Simpanan\TarikSaldoController@history')->name('tarik-saldo.history');
        Route::get('riwayat/cetak/{id}', 'Simpan_Pinjam\Simpanan\TarikSaldoController@print')->name('tarik-saldo.print');
        Route::get('riwayat/cetak/show/{id}', 'Simpan_Pinjam\Simpanan\TarikSaldoController@print_show')->name('tarik-saldo.print-show');

        Route::resource('tarik-saldo', 'Simpan_Pinjam\Simpanan\TarikSaldoController');
        Route::get('tarik-saldo/modal/{id}', 'Simpan_Pinjam\Simpanan\TarikSaldoController@modal')->name('tarik-saldo.konfirmasi');
        Route::get('tarik-saldo/modal/delete/{id}', 'Simpan_Pinjam\Simpanan\TarikSaldoController@modal_delete')->name('tarik-saldo.modal-delete');
        Route::post('tarik-saldo/saldo', 'Simpan_Pinjam\Simpanan\TarikSaldoController@saldo')->name('tarik-saldo.saldo');
    });

    #Pinjaman
    Route::prefix('pinjaman')->group(function () {
        Route::resource('pengajuan', 'Simpan_Pinjam\Pinjaman\PengajuanController');

        Route::get('pengajuan/konfirmasi/{id}', 'Simpan_Pinjam\Pinjaman\PengajuanController@konfirmasi')->name('pengajuan.konfirmasi');
        Route::get('pengajuan/cetak/{id}', 'Simpan_Pinjam\Pinjaman\PengajuanController@print')->name('pengajuan.print');
        Route::get('pengajuan/cetak/show/{id}', 'Simpan_Pinjam\Pinjaman\PengajuanController@print_show')->name('pengajuan.print-show');
        Route::get('pengajuan/modal/{id}', 'Simpan_Pinjam\Pinjaman\PengajuanController@modal')->name('pengajuan.modal');
        Route::post('pengajuan/limit', 'Simpan_Pinjam\Pinjaman\PengajuanController@limit')->name('pengajuan.limit');

        Route::resource('angsuran', 'Simpan_Pinjam\Pinjaman\AngsuranController');
        Route::post('angsuran/bayar', 'Simpan_Pinjam\Pinjaman\AngsuranController@bayar')->name('angsuran.bayar');
        Route::get('angsuran/cetak/show/{id}', 'Simpan_Pinjam\Pinjaman\AngsuranController@print_show')->name('angsuran.print-show');
        Route::get('angsuran/konfirmasi/{id}', 'Simpan_Pinjam\Pinjaman\AngsuranController@konfirmasi')->name('angsuran.konfirmasi');
        Route::get('angsuran/modal/{id}', 'Simpan_Pinjam\Pinjaman\AngsuranController@modal')->name('angsuran.modal');

        Route::resource('tempo', 'Simpan_Pinjam\Pinjaman\JatuhTempoController');
        Route::post('tempo/bayar', 'Simpan_Pinjam\Pinjaman\JatuhTempoController@bayar')->name('tempo.bayar');
        Route::get('tempo/cetak/show/{id}', 'Simpan_Pinjam\Pinjaman\JatuhTempoController@print_show')->name('tempo.print-show');
        Route::get('tempo/konfirmasi/{id}', 'Simpan_Pinjam\Pinjaman\JatuhTempoController@konfirmasi')->name('tempo.konfirmasi');
        Route::get('tempo/modal/{id}', 'Simpan_Pinjam\Pinjaman\JatuhTempoController@modal')->name('tempo.modal');
    });

    #Laporan
    Route::prefix('laporan')->group(function () {
        Route::resource('jurnal', 'Simpan_Pinjam\Laporan\JurnalUmumController');
        Route::post('jurnal/cetak', 'Simpan_Pinjam\Laporan\JurnalUmumController@print_show')->name('jurnal.print-show');
        Route::get('jurnal/modal/{id}', 'Simpan_Pinjam\Laporan\JurnalUmumController@modal')->name('jurnal.modal');

        Route::get('anggota', 'Simpan_Pinjam\Laporan\DataAnggotaController@index')->name('data-anggota.index');
        Route::get('anggota/cetak/show', 'Simpan_Pinjam\Laporan\DataAnggotaController@print_show')->name('data-anggota.print-show');

        Route::get('simpanan', 'Simpan_Pinjam\Laporan\LaporanSimpananController@index')->name('lap-simpanan.index');
        Route::post('simpanan/show', 'Simpan_Pinjam\Laporan\LaporanSimpananController@show')->name('lap-simpanan.show');
        Route::get('simpanan/cetak/all', 'Simpan_Pinjam\Laporan\LaporanSimpananController@print_all')->name('lap-simpanan.print-all');
        Route::get('simpanan/cetak/show/{id}', 'Simpan_Pinjam\Laporan\LaporanSimpananController@print_show')->name('lap-simpanan.print-show');

        Route::get('pinjaman', 'Simpan_Pinjam\Laporan\LaporanPinjamanController@index')->name('lap-pinjaman.index');
        Route::post('pinjaman/show', 'Simpan_Pinjam\Laporan\LaporanPinjamanController@show')->name('lap-pinjaman.show');
        Route::get('pinjaman/cetak/all', 'Simpan_Pinjam\Laporan\LaporanPinjamanController@print_all')->name('lap-pinjaman.print-all');
        Route::get('pinjaman/cetak/show/{id}', 'Simpan_Pinjam\Laporan\LaporanPinjamanController@print_show')->name('lap-pinjaman.print-show');

        Route::get('shu', 'Simpan_Pinjam\Laporan\SHUController@index')->name('shu.index');
        Route::post('shu/cetak', 'Simpan_Pinjam\Laporan\SHUController@print_show')->name('shu.print-show');
        Route::post('shu/show', 'Simpan_Pinjam\Laporan\SHUController@show_data')->name('shu.show-data');

        Route::get('buku-besar', 'Simpan_Pinjam\Laporan\BukuBesarController@index')->name('buku-besar.index');
        Route::post('buku-besar/show', 'Simpan_Pinjam\Laporan\BukuBesarController@show_data')->name('buku-besar.show-data');
        Route::post('buku-besar/cetak', 'Simpan_Pinjam\Laporan\BukuBesarController@print_show')->name('buku-besar.print-show');

        Route::get('ekuitas', 'Simpan_Pinjam\Laporan\EkuitasController@index')->name('ekuitas.index');
        Route::post('ekuiats/show', 'Simpan_Pinjam\Laporan\EkuitasController@show_data')->name('ekuitas.show-data');
        Route::post('ekuitas/cetak', 'Simpan_Pinjam\Laporan\EkuitasController@print_show')->name('ekuitas.print-show');

        Route::get('keuangan', 'Simpan_Pinjam\Laporan\KeuanganController@index')->name('keuangan.index');
        Route::post('keuangan/show', 'Simpan_Pinjam\Laporan\KeuanganController@show_data')->name('keuangan.show-data');
        Route::post('keuangan/cetak', 'Simpan_Pinjam\Laporan\KeuanganController@print_show')->name('keuangan.print-show');
    });

    #Pengaturan
    Route::prefix('pengaturan')->group(function () {
        Route::resource('list', 'Simpan_Pinjam\Pengaturan\PengaturanController');
        // Route::get('list/modal/{id}', 'Simpan_Pinjam\Pengaturan\PengaturanController@modal')->name('list.modal');
        Route::get('list/modal-all/{id}', 'Simpan_Pinjam\Pengaturan\PengaturanController@modal_all')->name('list.modal-all');
    });
});
