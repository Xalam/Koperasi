<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Supplier\SupplierModel;
use Illuminate\Http\Request;

class DataSupplierController extends Controller
{
    public function dataSupplier($id) {
        return SupplierModel::where('id', $id)
                            ->orderBy('nama')
                            ->get();
    }

    public function dataReturSupplier($id) {
        return SupplierModel::select('supplier.nama')
                            ->join('pembelian', 'pembelian.id_supplier', '=', 'supplier.id')
                            ->where('pembelian.id', $id)
                            ->get();
    }

    public function dataHutangSupplier($id) {
        return SupplierModel::select('supplier.nama AS nama', 'supplier.kode AS kode')
                            ->join('pembelian', 'pembelian.id_supplier', '=', 'supplier.id')
                            ->where('pembelian.id', $id)
                            ->get();
    }
}
