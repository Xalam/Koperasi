<?php

namespace App\Http\Controllers\Toko\Laporan\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanMasterController extends Controller
{
    public function index() {
        return view ('toko.laporan.master.index');
    }
}
