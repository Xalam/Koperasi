<?php

namespace App\Http\Controllers\Toko\Laporan\Penjualan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanPenjualanController extends Controller
{
    public function index() {
        return view ('toko.laporan.penjualan.index');
    }
}
