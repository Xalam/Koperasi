<?php

namespace App\Http\Controllers\Toko\Master\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Supplier\SupplierModel;

class SupplierController extends Controller
{
    public function index() {
        $supplier = SupplierModel::all();

        return view('toko.master.supplier.index', compact('supplier'));
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
