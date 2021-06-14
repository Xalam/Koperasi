<?php

namespace App\Http\Controllers\Toko\Master\Akun;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Akun\AkunModel;

class AkunController extends Controller
{
    public function index() {
        $akun = AkunModel::all();

        return view('toko.master.akun.index', compact('akun'));
    }

    public function store(Request $request) {
        AkunModel::create($request->all());

        return redirect('/toko/master/akun');
    }

    public function update(Request $request) {
        AkunModel::where('id', $request->id)->update($request->all());

        $akun = AkunModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'akun' => $akun]);
    }

    public function delete(Request $request) {
        AkunModel::where('id', $request->id)->delete();

        $akun = AkunModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'akun' => $akun]);
    }
}
