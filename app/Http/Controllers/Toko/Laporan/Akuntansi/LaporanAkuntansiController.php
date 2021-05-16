<?php

namespace App\Http\Controllers\Toko\Laporan\Akuntansi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanAkuntansiController extends Controller
{
    public function index() {
        return view ('toko.laporan.akuntansi.index');
    }
}
