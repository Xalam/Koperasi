<?php

namespace App\Http\Controllers\Transaksi\Pembelian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaksi\PembayaranModel;
use App\Models\Transaksi\Pembelian\PembelianModel;
use App\Models\Master\Barang\BarangModel;
use App\Models\Master\Supplier\SupplierModel;

class PembelianController extends Controller
{
    public function index() {
        $data_barang = BarangModel::orderBy('nama')
                                    ->get();

        $barang[''] = '-- Pilih Nama Barang --';
        foreach ($data_barang as $data) {
            $barang[$data->id] = $data->nama;
        }

        $data_barang = BarangModel::orderBy('kode')
                                    ->get();

        $kode_barang[''] = '-- Pilih Kode Barang --';
        foreach ($data_barang as $data) {
            $kode_barang[$data->id] = $data->kode;
        }
        
        $data_pembayaran = PembayaranModel::orderBy('nama')
                                            ->get();

        foreach ($data_pembayaran as $data) {
            $pembayaran[$data->id] = $data->nama;
        }

        $data_supplier = SupplierModel::orderBy('nama')
                                        ->get();

        $supplier[''] = '-- Pilih Nama Supplier --';
        foreach ($data_supplier as $data) {
            $supplier[$data->id] = $data->nama;
        }

        $data_supplier = SupplierModel::orderBy('kode')
                                        ->get();

        $kode_supplier[''] = '-- Pilih Kode Supplier --';
        foreach ($data_supplier as $data) {
            $kode_supplier[$data->id] = $data->kode;
        }


        return view('transaksi.pembelian.index', compact('barang', 'kode_barang', 'kode_supplier', 'pembayaran', 'supplier'));
    }

    public function show($nomor) {
        $pembelian = PembelianModel::where('pembelian.nomor', $nomor)
                                    ->join('barang', 'barang.id', '=', 'pembelian.id_barang')
                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                    ->get();

        return response()->json(['code'=>200, 'pembelian' => $pembelian]);
    }

    public function store(Request $request) {
        PembelianModel::create($request->all());
        
        return response()->json(['code'=>200]);
    }
}