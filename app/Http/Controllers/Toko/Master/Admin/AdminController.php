<?php

namespace App\Http\Controllers\Toko\Master\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Admin\AdminModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index() {
        $admin = AdminModel::all();

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

        return view('toko.master.admin.index', compact('admin', 'data_notified', 'data_notif'));
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

        return view('toko.master.admin.create', compact('data_notified', 'data_notif'));
    }

    public function store(Request $request) {
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

        return redirect('/toko/master/admin');
    }

    public function update(Request $request) {
        AdminModel::where('id', $request->id)->update($request->all());

        $admin = AdminModel::where('id', $request->id)->first();

        User::where('kode', $admin->kode)->update($request->all());

        return response()->json(['code' => 200, 'admin' => $admin]);
    }

    public function delete(Request $request) {
        AdminModel::where('id', $request->id)->delete();

        $admin = AdminModel::where('id', $request->id)->first();

        User::where('kode', $admin->kode)->delete();

        return response()->json(['code' => 200, 'admin' => $admin]);
    }
}
