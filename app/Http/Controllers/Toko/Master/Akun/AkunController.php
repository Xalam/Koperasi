<?php

namespace App\Http\Controllers\Toko\Master\Akun;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Akun\AkunJenisModel;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Akun\AkunModel;
use App\Models\Toko\Master\Barang\BarangModel;

class AkunController extends Controller
{
    public function index() {
        $akun = AkunModel::orderBy('kode')->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            } else {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 0
                ]);
            }
        }

        $data_notif = BarangModel::where('alert_status', 1)->get();

        return view('toko.master.akun.index', compact('akun', 'data_notified', 'data_notif'));
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

        return view('toko.master.akun.create', compact('data_notified', 'data_notif'));
    }

    public function store(Request $request) {
        $jenis_akun = AkunJenisModel::where('id', substr($request->input('kode'), 0, 1))->first();
        if ($jenis_akun->nama == 'Debit') {
            AkunModel::create([
                'kode' => $request->input('kode'),
                'nama' => $request->input('nama'),
                'debit' => $request->input('saldo'),
                'kredit' => 0
            ]);
        } else {
            AkunModel::create([
                'kode' => $request->input('kode'),
                'nama' => $request->input('nama'),
                'debit' => 0,
                'kredit' => $request->input('saldo')
            ]);
        }

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
