<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianBarangModel;
use App\Models\Toko\Transaksi\ReturTitipJual\ReturTitipJualBarangModel;
use App\Models\Toko\Transaksi\TitipJual\TitipJualDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataBarangController extends Controller
{
    public function dataBarang($id) {
        return BarangModel::where('id', $id)
                            ->orderBy('nama')
                            ->get();
    }
    public function dataBeliBarang($supplier) {
        return BarangModel::where('id_supplier', $supplier)
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

    public function dataReturTitipJualBarang($id) {
        return BarangModel::join('detail_titip_jual', 'detail_titip_jual.id_barang', '=', 'barang.id')
                            ->join('titip_jual', 'titip_jual.nomor', '=', 'detail_titip_jual.nomor')
                            ->select('barang.id AS id', 'barang.kode AS kode', 'barang.nama AS nama', 
                                    'detail_titip_jual.jumlah AS jumlah', 'detail_titip_jual.harga_satuan AS harga')
                            ->where('titip_jual.id', $id)
                            ->get();
    }

    public function dataReturTitipJualDetailBarang($nomor, $id) {
        return TitipJualDetailModel::leftJoin('detail_retur_titip_jual', function($join) {
                                        $join->on('detail_retur_titip_jual.id_barang', '=', 'detail_titip_jual.id_barang')
                                            ->on('detail_titip_jual.nomor', '=', 'detail_retur_titip_jual.nomor_titip_jual');
                                    })
                                ->select(DB::raw('IFNULL(SUM(detail_retur_titip_jual.jumlah), 0)  AS jumlah_retur'), 
                                        'detail_titip_jual.jumlah AS jumlah_beli', 'detail_titip_jual.harga_satuan AS harga')
                                ->where('detail_titip_jual.nomor', $nomor)
                                ->where('detail_titip_jual.id_barang', $id)
                                ->first();
    }
}
