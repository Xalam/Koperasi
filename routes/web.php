<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Toko\LoginController;

use App\Http\Controllers\Toko\DataBarangController;
use App\Http\Controllers\Toko\DataSupplierController;
use App\Http\Controllers\Toko\DataPelangganController;
use App\Http\Controllers\Toko\NomorTransaksiController;

use App\Http\Controllers\Toko\Transaksi\Pembelian\PembelianController;
use App\Http\Controllers\Toko\Transaksi\Penjualan\PenjualanController;
use App\Http\Controllers\Toko\Transaksi\Retur\ReturPembelianController;
use App\Http\Controllers\Toko\Transaksi\Hutang\HutangController;

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

Route::get('/', function () {
    return view('welcome');
});

//API
Route::group(['prefix' => 'api'], function () {
    Route::get('/data-barang/{id}', [DataBarangController::class, 'dataBarang']);
    Route::get('/data-retur-barang/{id}', [DataBarangController::class, 'dataReturBarang']);
    Route::get('/data-supplier/{id}', [DataSupplierController::class, 'dataSupplier']);
    Route::get('/data-retur-supplier/{id}', [DataSupplierController::class, 'dataReturSupplier']);
    Route::get('/data-hutang-supplier/{id}', [DataSupplierController::class, 'dataHutangSupplier']);
    Route::get('/data-pelanggan/{id}', [DataPelangganController::class, 'dataPelanggan']);
    Route::get('/nomor-pembelian/{tanggal}', [NomorTransaksiController::class, 'nomorPembelian']);
    Route::get('/nomor-penjualan/{tanggal}', [NomorTransaksiController::class, 'nomorPenjualan']);
    Route::get('/nomor-angsuran/{tanggal}', [NomorTransaksiController::class, 'nomorAngsuran']);
});

Route::group(['prefix' => 'toko'], function () {
    //Login
    Route::get('/login', [LoginController::class, 'index']);
    
    //Transaksi
    Route::group(['prefix' => 'transaksi'], function () {
        Route::group(['prefix' => 'pembelian'], function () {
            Route::get('/', [PembelianController::class, 'index']);
            Route::post('/store', [PembelianController::class, 'store']);
            Route::post('/beli', [PembelianController::class, 'buy']);
            Route::post('/cancel', [PembelianController::class, 'cancel']);
            Route::get('/{nomor}', [PembelianController::class, 'show']);
        });
        
        Route::group(['prefix' => 'penjualan'], function () {
            Route::get('/', [PenjualanController::class, 'index']);
            Route::post('/store', [PenjualanController::class, 'store']);
            Route::post('/jual', [PenjualanController::class, 'sell']);
            Route::post('/cancel', [PenjualanController::class, 'cancel']);
            Route::get('/{nomor}', [PenjualanController::class, 'show']);
        });
        
        Route::group(['prefix' => 'retur-pembelian'], function () {
            Route::get('/', [ReturPembelianController::class, 'index']);
            Route::post('/store', [ReturPembelianController::class, 'store']);
            Route::post('/retur', [ReturPembelianController::class, 'retur']);
            Route::post('/cancel', [ReturPembelianController::class, 'cancel']);
            Route::get('/{nomor}', [ReturPembelianController::class, 'show']);
        });
        
        Route::group(['prefix' => 'hutang'], function () {
            Route::get('/', [HutangController::class, 'index']);
            Route::post('/store', [HutangController::class, 'store']);
            Route::post('/cancel', [HutangController::class, 'cancel']);
            Route::get('/{nomor_beli}', [HutangController::class, 'show']);
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
        
        Route::group(['prefix' => 'retur-pembelian'], function () {
            Route::get('/', [LaporanReturPembelianController::class, 'index']);
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
            Route::post('/update', [BarangController::class, 'update']);
            Route::post('/delete', [BarangController::class, 'delete']);
        });
        
        Route::group(['prefix' => 'admin'], function () {
            Route::get('/', [AdminController::class, 'index']);
            Route::post('/store', [AdminController::class, 'store']);
            Route::post('/update', [AdminController::class, 'update']);
            Route::post('/delete', [AdminController::class, 'delete']);
        });
        
        Route::group(['prefix' => 'akun'], function () {
            Route::get('/', [AkunController::class, 'index']);
            Route::post('/store', [AkunController::class, 'store']);
            Route::post('/update', [AkunController::class, 'update']);
            Route::post('/delete', [AkunController::class, 'delete']);
        });
        
        Route::group(['prefix' => 'pelanggan'], function () {
            Route::get('/', [PelangganController::class, 'index']);
            Route::post('/store', [PelangganController::class, 'store']);
            Route::post('/update', [PelangganController::class, 'update']);
            Route::post('/delete', [PelangganController::class, 'delete']);
        });
        
        Route::group(['prefix' => 'supplier'], function () {
            Route::get('/', [SupplierController::class, 'index']);
            Route::post('/store', [SupplierController::class, 'store']);
            Route::post('/update', [SupplierController::class, 'update']);
            Route::post('/delete', [SupplierController::class, 'delete']);
        });
    });
});