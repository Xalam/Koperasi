<?php

namespace App\Http\Controllers\Laporan\Pembelian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanPembelianController extends Controller
{
    public function index() {
        return view ('laporan.pembelian.index');
    }
}
