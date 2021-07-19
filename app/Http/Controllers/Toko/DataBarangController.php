<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianBarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataBarangController extends Controller
{
    public function dataBarang($id) {
        return BarangModel::where('id', $id)
                            ->orderBy('nama')
                            ->get();
    }

    public function dataReturBarang($id) {
        return BarangModel::join('detail_beli', 'detail_beli.id_barang', '=', 'barang.id')
                            ->join('pembelian', 'pembelian.nomor', '=', 'detail_beli.nomor')
                            ->select('barang.id AS id', 'barang.kode AS kode', 'barang.nama AS nama', 
                                    'detail_beli.jumlah AS jumlah', 'detail_beli.harga_satuan AS harga')
                            ->where('pembelian.id', $id)
                            ->get();
    }

    public function dataReturDetailBarang($nomor, $id) {
        return PembelianBarangModel::leftJoin('detail_retur', function($join) {
                                        $join->on('detail_retur.id_barang', '=', 'detail_beli.id_barang')
                                            ->on('detail_beli.nomor', '=', 'detail_retur.nomor_beli');
                                    })
                                ->select(DB::raw('IFNULL(SUM(detail_retur.jumlah), 0)  AS jumlah_retur'), 
                                        'detail_beli.jumlah AS jumlah_beli', 'detail_beli.harga_satuan AS harga')
                                ->where('detail_beli.nomor', $nomor)
                                ->where('detail_beli.id_barang', $id)
                                ->first();
    }
}
