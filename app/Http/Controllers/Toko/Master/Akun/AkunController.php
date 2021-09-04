<?php

namespace App\Http\Controllers\Toko\Master\Akun;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Akun\AkunJenisModel;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Akun\AkunModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AkunController extends Controller
{
    public function index() {
        $akun = AkunModel::orderBy('kode')->get();

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

        return view('toko.master.akun.index', compact('akun', 'data_notified', 'data_notif', 'data_notif_hutang'));
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

        return view('toko.master.akun.create', compact('data_notified', 'data_notif', 'data_notif_hutang'));
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
        
        $jenis_akun = AkunJenisModel::where('id', substr($request->input('kode'), 0, 1))->first();
        
        $data_exist = AkunModel::where('kode', $request->kode)->get();
        
        if (count($data_exist) > 0) {
            Session::flash('failed', 'Gagal');
        } else {
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

            Session::flash('success', 'Berhasil');
        }
        
        return view('toko.master.akun.create', compact('data_notified', 'data_notif'));
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
