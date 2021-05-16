<?php

namespace App\Http\Controllers\Transaksi\Penjualan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WaserbaController extends Controller
{
    public function index() {
        return view('transaksi.penjualan.index');
    }
}
