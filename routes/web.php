<?php

use App\Http\Controllers\Simpan_Pinjam\Dashboard\DashboardController;
use App\Http\Controllers\Simpan_Pinjam\Master\Anggota\AnggotaController;
use App\Http\Controllers\Simpan_Pinjam\Master\User\UserController;
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
Route::prefix('simpan-pinjam')->group(function () {

    #Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    #Login
    Route::get('login', [UserController::class, 'login'])->name('login');

    #Master
    Route::prefix('master')->group(function () {
        Route::resource('anggota', 'Simpan_Pinjam\Master\Anggota\AnggotaController');
        Route::get('anggota/modal/{id}', 'Simpan_Pinjam\Master\Anggota\AnggotaController@modal')->name('anggota.modal');
        Route::get('anggota/cetak/{id}', 'Simpan_Pinjam\Master\Anggota\AnggotaController@print')->name('anggota.print');

        Route::resource('akun', 'Simpan_Pinjam\Master\Akun\AkunController');
        Route::get('akun/modal/{id}', 'Simpan_Pinjam\Master\Akun\AkunController@modal')->name('akun.modal');
    });

    #Simpanan
    Route::prefix('simpanan')->group(function () {
        Route::resource('data', 'Simpan_Pinjam\Master\Simpanan\SimpananController');
        Route::get('data/cetak/{id}', 'Simpan_Pinjam\Master\Simpanan\SimpananController@print')->name('data.print');
        Route::get('data/cetak/show/{id}', 'Simpan_Pinjam\Master\Simpanan\SimpananController@print_show')->name('data.print-show');
        Route::get('data/cetak/modal/{id}', 'Simpan_Pinjam\Master\Simpanan\SimpananController@modal')->name('data.modal');
        Route::post('data/store-all', 'Simpan_Pinjam\Master\Simpanan\SimpananController@store_all')->name('data.store-all');

        Route::resource('saldo', 'Simpan_Pinjam\Master\Simpanan\SaldoController');
    });
});