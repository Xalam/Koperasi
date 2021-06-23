<?php

use App\Http\Controllers\Simpan_Pinjam\Dashboard\DashboardController;
use App\Http\Controllers\Toko\DataBarangController;
use App\Http\Controllers\Toko\DataSupplierController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Toko\LoginController;

use App\Http\Controllers\Toko\Transaksi\Pembelian\PembelianController;
use App\Http\Controllers\Toko\Transaksi\Penjualan\WaserbaController;
use App\Http\Controllers\Toko\Transaksi\Penjualan\ReturController;

use App\Http\Controllers\Toko\Master\Barang\BarangController;
use App\Http\Controllers\Toko\Master\Admin\AdminController;
use App\Http\Controllers\Toko\Master\Akun\AkunController;
use App\Http\Controllers\Toko\Master\Pelanggan\PelangganController;
use App\Http\Controllers\Toko\Master\Supplier\SupplierController;

use App\Http\Controllers\Toko\Laporan\Akuntansi\LaporanAkuntansiController;
use App\Http\Controllers\Toko\Laporan\Master\LaporanMasterController;
use App\Http\Controllers\Toko\Laporan\Pembelian\LaporanPembelianController;
use App\Http\Controllers\Toko\Laporan\Penjualan\LaporanPenjualanController;
use App\Http\Controllers\Toko\Laporan\Persediaan\LaporanPersediaanController;


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

Route::get('/', function () {
    return view('welcome');
});

//API
Route::group(['prefix' => 'api'], function () {
    Route::get('/data-barang/{id}', [DataBarangController::class, 'dataBarang']);
    Route::get('/data-supplier/{id}', [DataSupplierController::class, 'dataSupplier']);
});

Route::group(['prefix' => 'toko'], function () {
    //Login
    Route::get('/login', [LoginController::class, 'index']);
    
    //Transaksi
    Route::group(['prefix' => 'transaksi'], function () {
        Route::group(['prefix' => 'pembelian'], function () {
            Route::get('/', [PembelianController::class, 'index']);
            Route::post('/store', [PembelianController::class, 'store']);
            Route::get('/{nomor}', [PembelianController::class, 'show']);
        });
        
        Route::group(['prefix' => 'penjualan'], function () {
            Route::get('/', [WaserbaController::class, 'index']);
        });
        
        Route::group(['prefix' => 'retur-pembelian'], function () {
            Route::get('/', [ReturController::class, 'index']);
        });
    });

    //Laporan
    Route::group(['prefix' => 'laporan'], function () {
        Route::group(['prefix' => 'akuntansi'], function () {
            Route::get('/', [LaporanAkuntansiController::class, 'index']);
        });
        
        Route::group(['prefix' => 'data-master'], function () {
            Route::get('/', [LaporanMasterController::class, 'index']);
        });
        
        Route::group(['prefix' => 'pembelian'], function () {
            Route::get('/', [LaporanPembelianController::class, 'index']);
        });
        
        Route::group(['prefix' => 'penjualan'], function () {
            Route::get('/', [LaporanPenjualanController::class, 'index']);
        });
        
        Route::group(['prefix' => 'persediaan'], function () {
            Route::get('/', [LaporanPersediaanController::class, 'index']);
        });
    });

    //Master
    Route::group(['prefix' => 'master'], function () {
        Route::group(['prefix' => 'barang'], function () {
            Route::get('/', [BarangController::class, 'index']);
            Route::post('/store', [BarangController::class, 'store']);
        });
        
        Route::group(['prefix' => 'admin'], function () {
            Route::get('/', [AdminController::class, 'index']);
            Route::post('/store', [AdminController::class, 'store']);
        });
        
        Route::group(['prefix' => 'akun'], function () {
            Route::get('/', [AkunController::class, 'index']);
            Route::post('/store', [AkunController::class, 'store']);
        });
        
        Route::group(['prefix' => 'pelanggan'], function () {
            Route::get('/', [PelangganController::class, 'index']);
            Route::post('/store', [PelangganController::class, 'store']);
        });
        
        Route::group(['prefix' => 'supplier'], function () {
            Route::get('/', [SupplierController::class, 'index']);
            Route::post('/store', [SupplierController::class, 'store']);
        });
    });
});

//SIMPAN PINJAM
Route::prefix('simpan-pinjam')->group(function (){
    #Login
    Route::get('login', 'Simpan_Pinjam\Auth\LoginController@login')->name('login');
    Route::post('postlogin', 'Simpan_Pinjam\Auth\LoginController@post_login')->name('post-login');
    Route::get('logout', 'Simpan_Pinjam\Auth\LoginController@logout')->name('logout');

    Route::get('/', function () {
        return redirect()->route('login');
    });
});

//checkrole:admin,bendahara,bendahara_pusat,ketua_koperasi,simpan_pinjam
Route::group(['prefix' => 'simpan-pinjam', 'middleware' => ['auth', 'checkrole:admin,bendahara,bendahara_pusat,ketua_koperasi,simpan_pinjam']], function () {

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

        Route::resource('tempo', 'Simpan_Pinjam\Pinjaman\JatuhTempoController');
        Route::post('tempo/bayar', 'Simpan_Pinjam\Pinjaman\JatuhTempoController@bayar')->name('tempo.bayar');
        Route::get('tempo/cetak/show/{id}', 'Simpan_Pinjam\Pinjaman\JatuhTempoController@print_show')->name('tempo.print-show');
        Route::get('tempo/konfirmasi/{id}', 'Simpan_Pinjam\Pinjaman\JatuhTempoController@konfirmasi')->name('tempo.konfirmasi');
    });

    #Laporan
    Route::prefix('laporan')->group(function () {
        Route::resource('jurnal', 'Simpan_Pinjam\Laporan\JurnalUmumController');
        Route::post('jurnal/cetak', 'Simpan_Pinjam\Laporan\JurnalUmumController@print_show')->name('jurnal.print-show');
        Route::get('jurnal/{id}', 'Simpan_Pinjam\Laporan\JurnalUmumController@modal')->name('jurnal.modal');

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
    });
});