<?php

namespace App\Http\Controllers\Toko\Master\Barang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Barang\BarangModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class BarangController extends Controller
{
    public function index() {
        $barang = BarangModel::all();

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

        return view('toko.master.barang.index', compact('barang', 'data_notified', 'data_notif'));
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
        
        return view('toko.master.barang.create', compact('data_notified', 'data_notif'));
    }

    public function store(Request $request) {
        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            }
        }
        
        BarangModel::create([
            'kode' => $request->input('kode'),
            'nama' => $request->input('nama'),
            'hpp' => $request->input('hpp'),
            'harga_jual' => $request->input('harga_jual'),
            'minimal_grosir' => $request->input('minimal_grosir'),
            'harga_grosir' => $request->input('harga_grosir'),
            'stok_minimal' => $request->input('stok_minimal'),
            'satuan' => $request->input('satuan'),
            'foto' => $request->input('nama') .'.' . $request->file('foto')->getClientOriginalExtension(),
            'expired' => Carbon::now()
        ]);

        if ($request->file('foto')->isValid()) {
            $request->file('foto')->move(public_path('storage/toko/barang/foto/'), $request->input('nama') .'.' . $request->file('foto')->getClientOriginalExtension());
        }
        
        Session::flash('success', 'Berhasil');
        return view('toko.master.barang.create', compact('data_notified', 'data_notif'));
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

    public function removeNotification($id) {
        BarangModel::where('id', $id)->update([
            'alert_status' => 0
        ]);

        $data_barang = BarangModel::where('alert_status', 1)->get();

        return response()->json(['code' => 200, 'data_barang' => $data_barang]);
    }
}