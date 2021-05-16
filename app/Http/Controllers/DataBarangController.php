<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang\BarangModel;
use Illuminate\Http\Request;

class DataBarangController extends Controller
{
    public function dataBarang($id) {
        return BarangModel::where('id', $id)
                            ->orderBy('nama')
                            ->get();
    }
}
