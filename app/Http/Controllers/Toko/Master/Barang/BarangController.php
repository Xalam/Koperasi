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

    public function update(Request $request) {
        BarangModel::where('id', $request->id)->update($request->all());

        $barang = BarangModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'barang' => $barang]);
    }

    public function delete(Request $request) {
        BarangModel::where('id', $request->id)->delete();

        $barang = BarangModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'barang' => $barang]);
    }
}
