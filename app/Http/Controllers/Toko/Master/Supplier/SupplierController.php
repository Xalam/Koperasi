<?php

namespace App\Http\Controllers\Toko\Master\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Supplier\SupplierModel;

class SupplierController extends Controller
{
    public function index() {
        $supplier = SupplierModel::all();

        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            }
        }

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
        
        return view('toko.master.supplier.create', compact('data_notified', 'data_notif'));
    }

    public function store(Request $request) {
        SupplierModel::create($request->all());

        return redirect('/toko/master/supplier');
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
