<?php

namespace App\Http\Controllers\Toko\Master\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Anggota\AnggotaModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AnggotaController extends Controller
{
    public function index() {
        $anggota = AnggotaModel::all();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok_etalase <= $data->stok_minimal || $data->stok_gudang <= $data->stok_minimal) {
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

        HutangModel::where(DB::raw('DATE_ADD(DATE(NOW()), INTERVAL 3 DAY)'), '>=', DB::raw('DATE(jatuh_tempo)'))->update([
            'alert_status' => 1
        ]);

        $data_notif_hutang = HutangModel::join('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                    ->select('hutang.*', 'supplier.nama AS nama_supplier')
                                    ->get();

        return view('toko.master.anggota.index', compact('anggota', 'data_notified', 'data_notif', 'data_notif_hutang'));
    }
    
    public function create() {
        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok_etalase <= $data->stok_minimal || $data->stok_gudang <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            } else {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 0
                ]);
            }
        }

        HutangModel::where(DB::raw('DATE_ADD(DATE(NOW()), INTERVAL 3 DAY)'), '>=', DB::raw('DATE(jatuh_tempo)'))->update([
            'alert_status' => 1
        ]);

        $data_notif_hutang = HutangModel::join('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                    ->select('hutang.*', 'supplier.nama AS nama_supplier')
                                    ->get();
        
        return view('toko.master.anggota.create', compact('data_notified', 'data_notif', 'data_notif_hutang'));
    }

    public function store(Request $request) {
        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok_etalase <= $data->stok_minimal || $data->stok_gudang <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            } else {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 0
                ]);
            }
        }
        
        $dataExist = AnggotaModel::where('username', $request->username)->get();

        if (count($dataExist) <= 0) {
            if ($request->file('foto')->isValid()) {
                AnggotaModel::create([
                    'kd_anggota' => 'ANG-' . $request->input('username'),
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
    
            Session::flash('success', 'Berhasil');
        } else {
            Session::flash('failed', 'Username sudah ada');
        }
        return view('toko.master.anggota.create', compact('data_notified', 'data_notif'));
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
