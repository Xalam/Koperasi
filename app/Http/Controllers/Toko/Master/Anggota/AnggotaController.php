<?php

namespace App\Http\Controllers\Toko\Master\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Anggota\AnggotaModel;
use App\Models\Toko\Master\Barang\BarangModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

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
        if ($request->file('foto')->isValid()) {
            AnggotaModel::create([
                'kd_anggota' => $request->input('kd_anggota'),
                'nama_anggota' => $request->input('nama_anggota'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'agama' => $request->input('agama'),
                'tempat_lahir' => $request->input('tempat_lahir'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'alamat' => $request->input('alamat'),
                'no_hp' => $request->input('no_hp'),
                'no_wa' => $request->input('no_wa'),
                'foto' => $request->input('nama_anggota') .'.' . $request->file('foto')->getClientOriginalExtension(),
                'status' => $request->input('status'),
                'jabatan' => $request->input('jabatan'),
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'password' => Hash::make($request->input('password')),
                'role' => $request->input('role'),
                'gaji' => $request->input('gaji'),
                'limit_gaji' => $request->input('limit_gaji')
            ]);

            $request->file('foto')->move(public_path('document/toko/anggota/foto/'), $request->input('nama_anggota') .'.' . $request->file('foto')->getClientOriginalExtension());
        }

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
