<?php

namespace App\Http\Controllers;

use App\Models\Master\Supplier\SupplierModel;
use Illuminate\Http\Request;

class DataSupplierController extends Controller
{
    public function dataSupplier($id) {
        return SupplierModel::where('id', $id)
                            ->orderBy('nama')
                            ->get();
    }
}
