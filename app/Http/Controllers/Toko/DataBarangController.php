<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use Illuminate\Http\Request;

class DataBarangController extends Controller
{
    public function dataBarang($id) {
        return BarangModel::where('id', $id)
                            ->orderBy('nama')
                            ->get();
    }

    public function dataReturBarang($id) {
        return BarangModel::select('barang.*')
                            ->join('pembelian_barang', 'pembelian_barang.id_barang', '=', 'barang.id')
                            ->join('pembelian', 'pembelian.nomor', '=', 'pembelian_barang.nomor')
                            ->where('pembelian.id', $id)
                            ->get();
    }
}
