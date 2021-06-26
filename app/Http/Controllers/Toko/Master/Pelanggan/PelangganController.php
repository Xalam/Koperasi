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

    public function update(Request $request) {
        PelangganModel::where('id', $request->id)->update($request->all());

        $pelanggan = PelangganModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'pelanggan' => $pelanggan]);
    }

    public function delete(Request $request) {
        PelangganModel::where('id', $request->id)->delete();

        $pelanggan = PelangganModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'pelanggan' => $pelanggan]);
    }
}
