<?php

namespace App\Http\Controllers\Toko\Master\Barang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Barang\BarangModel;

class BarangController extends Controller
{
    public function index() {
        $barang = BarangModel::all();

        return view('toko.master.barang.index', compact('barang'));
    }

    public function store(Request $request) {
        BarangModel::create($request->all());
        
        return redirect('/toko/master/barang');
    }
}
