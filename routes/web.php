<?php

use App\Http\Controllers\Toko\DataBarangController;
use App\Http\Controllers\Toko\DataSupplierController;
use App\Http\Controllers\Toko\DataPelangganController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Toko\LoginController;

use App\Http\Controllers\Toko\Transaksi\Pembelian\PembelianController;
use App\Http\Controllers\Toko\Transaksi\Penjualan\PenjualanController;
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
    Route::get('/data-pelanggan/{id}', [DataPelangganController::class, 'dataPelanggan']);
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