<?php

namespace App\Http\Controllers\Toko\Transaksi\Retur;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use App\Models\Toko\Transaksi\Retur\ReturPembelianBarangModel;
use App\Models\Toko\Transaksi\Retur\ReturPembelianModel;
use Illuminate\Http\Request;

class ReturPembelianController extends Controller
{
    public function index() {
        $data_nomor_beli = PembelianModel::orderBy('nomor')->get();

        $nomor_beli[0] = "-- Pilih Nomor Beli --";
        foreach ($data_nomor_beli as $data) {
            $nomor_beli[$data->id] = $data->nomor;
        }

        return view('toko.transaksi.retur.index', compact('nomor_beli'));
    }

    public function show($nomor) {
        $barang_retur = ReturPembelianBarangModel::where('retur_barang.nomor', $nomor)
                                    ->join('barang', 'barang.id', '=', 'retur_barang.id_barang')
                                    ->select('retur_barang.nomor AS nomor_retur', 'retur_barang.jumlah AS jumlah_retur',
                                            'retur_barang.total_harga AS total_harga', 'retur_barang.id_barang AS id_barang',
                                            'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                            'barang.harga_beli AS harga_beli')
                                    ->get();

        $supplier_retur = ReturPembelianModel::where('retur.nomor', $nomor)
                                    ->join('supplier', 'supplier.id', '=', 'retur.id_supplier')
                                    ->join('pembelian', 'pembelian.id', '=', 'retur.nomor_beli')
                                    ->select('retur.tanggal AS tanggal', 'retur.nomor AS nomor_retur', 'pembelian.id AS id_beli',
                                            'pembelian.nomor AS nomor_beli', 'supplier.id AS id_supplier', 'supplier.nama AS nama_supplier')
                                    ->first();

        return response()->json(['code'=>200, 'barang_retur' => $barang_retur, 'supplier_retur' => $supplier_retur]);
    }

    public function store(Request $request) {
        $barang = ReturPembelianBarangModel::where('nomor', $request->nomor)
                                        ->where('id_barang', $request->id_barang)->first();

        if ($barang) {
            ReturPembelianBarangModel::where('id_barang', $request->id_barang)->update([
                'jumlah' => $barang->jumlah + $request->jumlah, 
                'total_harga' => $barang->total_harga + $request->total_harga
                ]);
        } else {
            ReturPembelianBarangModel::create($request->all());
        }
        
        return response()->json(['code'=>200]);
    }

    public function retur(Request $request) {
        $nomor = $request->input('nomor');

        ReturPembelianBarangModel::where('nomor', $nomor)->update(['submited' => 1]);

        $data_barang = ReturPembelianBarangModel::where('nomor', $nomor)->get();

        foreach ($data_barang as $data) {
            $barang = BarangModel::where('id', $data->id_barang)->first();

            BarangModel::where('id', $data->id_barang)->update([
                'stok' => $barang->stok - $data->jumlah]);
        }

        $data_retur = ReturPembelianModel::where('nomor', $nomor)->first();

        if (!$data_retur) {
            ReturPembelianModel::create([
                'tanggal' => $request->input('tanggal'),
                'nomor' => $request->input('nomor'),
                'nomor_beli' => $request->input('id_beli'),
                'id_supplier' => $request->input('nama_supplier'),
                'jumlah_harga' => $request->input('jumlah_harga')
            ]);
        } else {
            ReturPembelianModel::where('nomor', $nomor)->update([
                'tanggal' => $request->input('tanggal'),
                'jumlah_harga' => $request->input('jumlah_harga')
            ]);
        }
        
        return redirect('toko/transaksi/retur-pembelian');
    }

    public function cancel(Request $request) {
        $nomor = $request->input('nomor');

        ReturPembelianModel::where('nomor', $nomor)->delete();
        ReturPembelianBarangModel::where('nomor', $nomor)->delete();
        
        return response()->json(['code'=>200]);
    }
}
