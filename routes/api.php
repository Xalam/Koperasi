<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('API')->group(function () {
    Route::post('/login', 'LoginController@login');

    Route::prefix('simpan-pinjam')->group(function () {
        Route::get('saldo', 'Simpan_Pinjam\SaldoController@index');
        Route::post('saldo-deposit', 'Simpan_Pinjam\SaldoController@deposit');
        Route::post('saldo-tarik', 'Simpan_Pinjam\SaldoController@withdraw');
        Route::post('saldo-upload', 'Simpan_Pinjam\SaldoController@upload_transfer');
        Route::get('saldo-upload-info', 'Simpan_Pinjam\SaldoController@upload_info');

        Route::get('settings', 'Simpan_Pinjam\PengaturanController@index');

        Route::post('pinjaman', 'Simpan_Pinjam\PinjamanController@index');

        Route::get('pinjaman-history', 'Simpan_Pinjam\PinjamanController@history');
        Route::get('pinjaman-kode', 'Simpan_Pinjam\PinjamanController@kode');
        Route::get('pinjaman-angsuran', 'Simpan_Pinjam\PinjamanController@angsuran_pinjaman');

        Route::post('angsuran', 'Simpan_Pinjam\PinjamanController@angsuran');
        Route::post('angsuran-lunas', 'Simpan_Pinjam\PinjamanController@angsuran_lunas');
        Route::post('angsuran-upload', 'Simpan_Pinjam\PinjamanController@upload_transfer_angsuran');
        Route::get('angsuran-upload-info', 'Simpan_Pinjam\PinjamanController@upload_info_angsuran');

        Route::get('notifikasi', 'Simpan_Pinjam\NotifikasiController@index');
        Route::get('notifikasi-unread', 'Simpan_Pinjam\NotifikasiController@unread');
        Route::get('notifikasi/{id}', 'Simpan_Pinjam\NotifikasiController@detail');
        Route::delete('notifikasi/delete', 'Simpan_Pinjam\NotifikasiController@delete');
    });

    Route::prefix('toko')->group(function () {
    });
});
