<?php

namespace App\Http\Controllers\Toko\Master\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Anggota\AnggotaModel;
use App\Models\Toko\Master\Barang\BarangModel;

class AnggotaController extends Controller
{
    public function index() {
        $anggota = AnggotaModel::all();

        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            }
        }

        return view('toko.master.anggota.index', compact('anggota', 'data_notified', 'data_notif'));
    }
    
    public function create() {
        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            }
        }
        
        return view('toko.master.anggota.create', compact('data_notified', 'data_notif'));
    }

    public function store(Request $request) {
        AnggotaModel::create($request->all());

        return redirect('/toko/master/anggota');
    }

    public function update(Request $request) {
        AnggotaModel::where('id', $request->id)->update($request->all());

        $anggota = AnggotaModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'anggota' => $anggota]);
    }

    public function delete(Request $request) {
        AnggotaModel::where('id', $request->id)->delete();

        $anggota = AnggotaModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'anggota' => $anggota]);
    }
}
