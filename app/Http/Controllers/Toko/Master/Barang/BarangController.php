<?php

namespace App\Http\Controllers\Master\Barang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Barang\BarangModel;

class BarangController extends Controller
{
    public function index() {
        $barang = BarangModel::all();

        return view('master.barang.index', compact('barang'));
    }

    public function store(Request $request) {
        BarangModel::create($request->all());
        
        return redirect('/master/barang');
    }
}
