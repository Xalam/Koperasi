<?php

namespace App\Http\Controllers\Toko\Transaksi\Pembelian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\PembayaranModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianBarangModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use Carbon\Carbon;

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


        return view('toko.transaksi.pembelian.index', compact('barang', 'kode_barang', 'kode_supplier', 'pembayaran', 'supplier'));
    }

    public function show($nomor) {
        $barang_pembelian = PembelianBarangModel::where('pembelian_barang.nomor', $nomor)
                                    ->join('barang', 'barang.id', '=', 'pembelian_barang.id_barang')
                                    ->select('pembelian_barang.nomor AS nomor_pembelian', 'pembelian_barang.jumlah AS jumlah_barang',
                                            'pembelian_barang.total_harga AS total_harga', 'pembelian_barang.id_barang AS id_barang',
                                            'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                            'barang.harga_beli AS harga_beli')
                                    ->get();

        $supplier_pembelian = PembelianModel::where('pembelian.nomor', $nomor)
                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                    ->select('pembelian.tanggal AS tanggal', 'pembelian.nomor AS nomor_pembelian', 'pembelian.id_supplier AS id_supplier',
                                            'pembelian.jumlah_bayar AS jumlah_bayar', 'pembelian.jumlah_kembalian AS jumlah_kembalian', 
                                            'pembelian.pembayaran AS pembayaran', 'supplier.alamat AS alamat', 'supplier.telepon AS telepon')
                                    ->first();

        return response()->json(['code'=>200, 'barang_pembelian' => $barang_pembelian, 'supplier_pembelian' => $supplier_pembelian]);
    }

    public function store(Request $request) {
        $barang = PembelianBarangModel::where('nomor', $request->nomor)
                                        ->where('id_barang', $request->id_barang)->first();

        if ($barang) {
            PembelianBarangModel::where('id_barang', $request->id_barang)->update([
                'jumlah' => $barang->jumlah + $request->jumlah, 
                'total_harga' => $barang->total_harga + $request->total_harga
                ]);
        } else {
            PembelianBarangModel::create($request->all());
        }
        
        return response()->json(['code'=>200]);
    }

    public function buy(Request $request) {
        $nomor = $request->input('nomor');

        PembelianBarangModel::where('nomor', $nomor)->update(['submited' => 1]);

        $data_barang = PembelianBarangModel::where('nomor', $nomor)->get();

        foreach ($data_barang as $data) {
            $barang = BarangModel::where('id', $data->id_barang)->first();

            BarangModel::where('id', $data->id_barang)->update([
                'stok' => $barang->stok + $data->jumlah]);
        }

        $data_pembelian = PembelianModel::where('nomor', $nomor)->first();

        if ($request->input('pembayaran') == 2) {
            $jumlah_bayar = $request->input('jumlah_bayar');
            $jumlah_kembalian = $request->input('jumlah_kembalian');
        } else {
            $jumlah_bayar = 0;
            $jumlah_kembalian = 0;
        }

        if (!$data_pembelian) {
            PembelianModel::create([
                'tanggal' => $request->input('tanggal'),
                'nomor' => $request->input('nomor'),
                'id_supplier' => $request->input('kode_supplier'),
                'jumlah_harga' => $request->input('jumlah_harga'),
                'jumlah_bayar' => $jumlah_bayar,
                'jumlah_kembalian' => $jumlah_kembalian,
                'pembayaran' => $request->input('pembayaran')
            ]);
        } else {
            PembelianModel::where('nomor', $nomor)->update([
                'tanggal' => $request->input('tanggal'),
                'jumlah_harga' => $request->input('jumlah_harga'),
                'jumlah_bayar' => $jumlah_bayar,
                'jumlah_kembalian' => $jumlah_kembalian,
                'pembayaran' => $request->input('pembayaran')
            ]);
        }
        
        return redirect('toko/transaksi/pembelian');
    }

    public function cancel(Request $request) {
        $nomor = $request->input('nomor');

        PembelianModel::where('nomor', $nomor)->delete();
        PembelianBarangModel::where('nomor', $nomor)->delete();
        
        return response()->json(['code'=>200]);
    }
}