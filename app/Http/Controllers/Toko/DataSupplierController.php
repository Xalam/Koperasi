<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Toko\Models\Master\Supplier\SupplierModel;
use Illuminate\Http\Request;

class DataSupplierController extends Controller
{
    public function dataSupplier($id) {
        return SupplierModel::where('id', $id)
                            ->orderBy('nama')
                            ->get();
    }
}
