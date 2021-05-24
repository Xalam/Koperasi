<?php

namespace App\Http\Controllers\Toko\Transaksi\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Master\Pelanggan\PelangganModel;
use App\Models\Toko\Transaksi\PembayaranModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanBarangModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use Illuminate\Http\Request;

class PenjualanController extends Controller
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

        $data_pelanggan = PelangganModel::orderBy('nama')
                                        ->get();

        $pelanggan[''] = '-- Pilih Nama Pelanggan --';
        foreach ($data_pelanggan as $data) {
            $pelanggan[$data->id] = $data->nama;
        }

        $data_pelanggan = PelangganModel::orderBy('kode')
                                        ->get();

        $kode_pelanggan[''] = '-- Pilih Kode Pelanggan --';
        foreach ($data_pelanggan as $data) {
            $kode_pelanggan[$data->id] = $data->kode;
        }

        return view('toko.transaksi.penjualan.index', compact('barang', 'kode_barang', 'kode_pelanggan', 'pembayaran', 'pelanggan'));
    }

    public function show($nomor) {
        $barang_penjualan = PenjualanBarangModel::where('penjualan_barang.nomor', $nomor)
                                    ->join('barang', 'barang.id', '=', 'penjualan_barang.id_barang')
                                    ->select('penjualan_barang.nomor AS nomor_penjualan', 'penjualan_barang.jumlah AS jumlah_barang',
                                            'penjualan_barang.total_harga AS total_harga', 'penjualan_barang.id_barang AS id_barang',
                                            'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                            'barang.harga_beli AS harga_beli')
                                    ->get();

        $pelanggan_penjualan = PenjualanModel::where('penjualan.nomor', $nomor)
                                    ->join('pelanggan', 'pelanggan.id', '=', 'penjualan.id_pelanggan')
                                    ->select('penjualan.tanggal AS tanggal', 'penjualan.nomor AS nomor_penjualan', 'penjualan.id_pelanggan AS id_pelanggan',
                                            'penjualan.jumlah_bayar AS jumlah_bayar', 'penjualan.jumlah_kembalian AS jumlah_kembalian', 
                                            'penjualan.pembayaran AS pembayaran', 'pelanggan.alamat AS alamat', 'pelanggan.telepon AS telepon')
                                    ->first();

        return response()->json(['code'=>200, 'barang_penjualan' => $barang_penjualan, 'pelanggan_penjualan' => $pelanggan_penjualan]);
    }

    public function store(Request $request) {
        $barang = PenjualanBarangModel::where('nomor', $request->nomor)
                                        ->where('id_barang', $request->id_barang)->first();

        if ($barang) {
            PenjualanBarangModel::where('id_barang', $request->id_barang)->update([
                'jumlah' => $barang->jumlah + $request->jumlah, 
                'total_harga' => $barang->total_harga + $request->total_harga
                ]);
        } else {
            PenjualanBarangModel::create($request->all());
        }
        
        return response()->json(['code'=>200]);
    }

    public function sell(Request $request) {
        $nomor = $request->input('nomor');

        PenjualanBarangModel::where('nomor', $nomor)->update(['submited' => 1]);

        $data_barang = PenjualanBarangModel::where('nomor', $nomor)->get();

        foreach ($data_barang as $data) {
            $barang = BarangModel::where('id', $data->id_barang)->first();

            BarangModel::where('id', $data->id_barang)->update([
                'stok' => $barang->stok - $data->jumlah]);
        }

        $data_penjualan = PenjualanModel::where('nomor', $nomor)->first();

        if ($request->input('pembayaran') == 0) {
            $jumlah_bayar = $request->input('jumlah_bayar');
            $jumlah_kembalian = $request->input('jumlah_kembalian');
        } else {
            $jumlah_bayar = 0;
            $jumlah_kembalian = 0;
        }

        if (!$data_penjualan) {
            PenjualanModel::create([
                'tanggal' => $request->input('tanggal'),
                'nomor' => $request->input('nomor'),
                'id_pelanggan' => $request->input('kode_pelanggan'),
                'jumlah_harga' => $request->input('jumlah_harga'),
                'jumlah_bayar' => $jumlah_bayar,
                'jumlah_kembalian' => $jumlah_kembalian,
                'pembayaran' => $request->input('pembayaran')
            ]);
        } else {
            PenjualanModel::where('nomor', $nomor)->update([
                'tanggal' => $request->input('tanggal'),
                'jumlah_harga' => $request->input('jumlah_harga'),
                'jumlah_bayar' => $jumlah_bayar,
                'jumlah_kembalian' => $jumlah_kembalian,
                'pembayaran' => $request->input('pembayaran')
            ]);
        }
        
        return redirect('toko/transaksi/penjualan');
    }

    public function cancel(Request $request) {
        $nomor = $request->input('nomor');

        PenjualanModel::where('nomor', $nomor)->delete();
        PenjualanBarangModel::where('nomor', $nomor)->delete();
        
        return response()->json(['code'=>200]);
    }
}
