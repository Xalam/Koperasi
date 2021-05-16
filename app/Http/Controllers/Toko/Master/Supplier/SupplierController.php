<?php

namespace App\Http\Controllers\Master\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Supplier\SupplierModel;

class SupplierController extends Controller
{
    public function index() {
        $supplier = SupplierModel::all();

        return view('master.supplier.index', compact('supplier'));
    }

    public function store(Request $request) {
        SupplierModel::create($request->all());

        return redirect('/master/supplier');
    }
}
