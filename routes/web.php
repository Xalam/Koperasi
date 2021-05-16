<?php

use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\DataSupplierController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;

use App\Http\Controllers\Transaksi\Pembelian\PembelianController;
use App\Http\Controllers\Transaksi\Penjualan\WaserbaController;
use App\Http\Controllers\Transaksi\Penjualan\ReturController;

use App\Http\Controllers\Master\Barang\BarangController;
use App\Http\Controllers\Master\Admin\AdminController;
use App\Http\Controllers\Master\Akun\AkunController;
use App\Http\Controllers\Master\Pelanggan\PelangganController;
use App\Http\Controllers\Master\Supplier\SupplierController;

use App\Http\Controllers\Laporan\Akuntansi\LaporanAkuntansiController;
use App\Http\Controllers\Laporan\Master\LaporanMasterController;
use App\Http\Controllers\Laporan\Pembelian\LaporanPembelianController;
use App\Http\Controllers\Laporan\Penjualan\LaporanPenjualanController;
use App\Http\Controllers\Laporan\Persediaan\LaporanPersediaanController;

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