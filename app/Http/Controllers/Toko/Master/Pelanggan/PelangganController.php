<?php

namespace App\Http\Controllers\Toko\Master\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Pelanggan\PelangganModel;

class PelangganController extends Controller
{
    public function index() {
        $pelanggan = PelangganModel::all();

        return view('toko.master.pelanggan.index', compact('pelanggan'));
    }

    public function store(Request $request) {
        PelangganModel::create($request->all());

        return redirect('/toko/master/pelanggan');
    }
}
