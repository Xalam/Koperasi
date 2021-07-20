<?php

namespace App\Http\Controllers\Toko\Master\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Supplier\SupplierModel;
use Illuminate\Support\Facades\Session;

class SupplierController extends Controller
{
    public function index() {
        $supplier = SupplierModel::all();

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

        return view('toko.master.supplier.index', compact('data_notified', 'data_notif', 'supplier'));
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

        $last_nomor = SupplierModel::all();

        if (count($last_nomor) > 0) {
            $kode = "SUP" . str_pad(count($last_nomor) + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $kode = "SUP" . str_pad(strval(1), 5, '0', STR_PAD_LEFT);
        }
        
        return view('toko.master.supplier.create', compact('data_notified', 'data_notif', 'kode'));
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
        
        $namaExist = SupplierModel::where('nama', $request->nama)->get();
        $emailExist = SupplierModel::where('email', $request->email)->get();

        if (count($namaExist) > 0) {
            Session::flash('failed', 'Nama supplier sudah ada');

        } else if (count($emailExist) > 0) {
            Session::flash('failed', 'Email supplier sudah ada');
        } else {
            SupplierModel::create($request->all());
    
            Session::flash('success', 'Berhasil');
        }
        return view('toko.master.supplier.create', compact('data_notified', 'data_notif'));
    }

    public function update(Request $request) {
        SupplierModel::where('id', $request->id)->update($request->all());

        $supplier = SupplierModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'supplier' => $supplier]);
    }

    public function delete(Request $request) {
        SupplierModel::where('id', $request->id)->delete();

        $supplier = SupplierModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'supplier' => $supplier]);
    }
}
