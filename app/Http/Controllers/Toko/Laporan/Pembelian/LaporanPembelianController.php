<?php

namespace App\Http\Controllers\Toko\Laporan\Pembelian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanPembelianController extends Controller
{
    public function index() {
        return view ('toko.laporan.pembelian.index');
    }
}
