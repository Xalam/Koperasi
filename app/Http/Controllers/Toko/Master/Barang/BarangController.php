<?php

namespace App\Http\Controllers\Toko\Master\Barang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Barang\BarangModel;

class BarangController extends Controller
{
    public function index() {
        $barang = BarangModel::all();

        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            }
        }

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
        BarangModel::create($request->all());
        
        return redirect('/toko/master/barang');
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
