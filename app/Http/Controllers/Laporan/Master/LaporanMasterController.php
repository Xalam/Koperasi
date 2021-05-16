<?php

namespace App\Http\Controllers\Laporan\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanMasterController extends Controller
{
    public function index() {
        return view ('laporan.master.index');
    }
}
