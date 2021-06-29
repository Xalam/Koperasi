<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Toko\DataAkunController;
use App\Http\Controllers\Toko\DataBarangController;
use App\Http\Controllers\Toko\DataSupplierController;
use App\Http\Controllers\Toko\DataAnggotaController;
use App\Http\Controllers\Toko\NomorTransaksiController;
use App\Http\Controllers\Toko\NomorJurnalController;

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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('welcome');
    });
});

Route::group(['prefix' => 'toko'], function () {
    //Login
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login/store', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register/store', [AuthController::class, 'store']);

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard', function () {
            return view('welcome');
        });

        Route::group(['middleware' => 'auth'], function () {
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