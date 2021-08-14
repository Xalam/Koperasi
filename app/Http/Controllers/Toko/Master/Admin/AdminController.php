<?php

namespace App\Http\Controllers\Toko\Master\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Admin\AdminModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function index() {
        $admin = AdminModel::all();

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

        return view('toko.master.admin.index', compact('admin', 'data_notified', 'data_notif_hutang', 'data_notif'));
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

        return view('toko.master.admin.create', compact('data_notified', 'data_notif', 'data_notif_hutang'));
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
        
        $nameExist = AdminModel::where('jabatan', $request->jabatan)
                                ->where('nama', $request->nama)
                                ->get();

        if (count($nameExist) > 0) {
            Session::flash('failed', 'Nama admin sudah ada');
        } else {
            AdminModel::create([
                'kode' => $request->input('kode'),
                'nama' => $request->input('nama'),
                'password' => Hash::make($request->input('password')),
                'jabatan' => $request->input('jabatan')
            ]);
    
            User::create([
                'kode' => $request->input('kode'),
                'nama' => $request->input('nama'),
                'password' => Hash::make($request->input('password')),
                'jabatan' => $request->input('jabatan')
            ]);
    
            Session::flash('success', 'Berhasil');
        }
        return view('toko.master.admin.create', compact('data_notified', 'data_notif'));
    }

    public function update(Request $request) {
        AdminModel::where('id', $request->id)->update($request->all());

        $admin = AdminModel::where('id', $request->id)->first();

        AdminModel::where('kode', $admin->kode)->update([
            'kode' => $request->input('kode'),
            'nama' => $request->input('nama'),
            'password' => Hash::make($request->input('password')),
            'jabatan' => $request->input('jabatan')
        ]);

        User::where('kode', $admin->kode)->update([
            'kode' => $request->input('kode'),
            'nama' => $request->input('nama'),
            'password' => Hash::make($request->input('password')),
            'jabatan' => $request->input('jabatan')
        ]);

        return response()->json(['code' => 200, 'admin' => $admin]);
    }

    public function delete(Request $request) {
        $admin = AdminModel::where('id', $request->id)->first();

        AdminModel::where('id', $request->id)->delete();
        User::where('kode', $admin->kode)->delete();

        return response()->json(['code' => 200, 'admin' => $admin]);
    }
}
