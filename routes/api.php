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
    Route::get('/anggota', 'Simpan_Pinjam\TestController@index');
    Route::post('/login', 'LoginController@login');

    Route::prefix('simpan-pinjam')->group(function () {
        Route::get('saldo', 'Simpan_Pinjam\SaldoController@index');
        Route::post('saldo-deposit', 'Simpan_Pinjam\SaldoController@deposit');
        Route::post('saldo-tarik', 'Simpan_Pinjam\SaldoController@withdraw');
    });

    Route::prefix('toko')->group(function () {
    });
});
