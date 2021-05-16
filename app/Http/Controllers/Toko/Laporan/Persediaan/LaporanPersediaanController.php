<?php

namespace App\Http\Controllers\Laporan\Persediaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanPersediaanController extends Controller
{
    public function index() {
        return view ('laporan.persediaan.index');
    }
}
