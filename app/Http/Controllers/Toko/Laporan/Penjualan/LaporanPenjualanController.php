<?php

namespace App\Http\Controllers\Toko\Laporan\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Toko\Transaksi\PembayaranModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request) {
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $type_pembayaran = $request->input('type_pembayaran');

        $data_pembayaran = PembayaranModel::all();

        $pembayaran[0] = "Semua";
        foreach ($data_pembayaran as $data) {
            $pembayaran[$data->id] = $data->nama;
        }

        if ($tanggal_awal && !$tanggal_akhir) {
            if ($type_pembayaran > 0) {
                $laporan_penjualan = PenjualanModel::where('tanggal', '>=', $tanggal_awal)
                                                    ->where('pembayaran', '=', $type_pembayaran)
                                                    ->join('penjualan_barang', 'penjualan_barang.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'penjualan_barang.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'penjualan_barang.jumlah AS jumlah', 
                                                            DB::raw('SUM(penjualan_barang.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            } else {
                $laporan_penjualan = PenjualanModel::where('tanggal', '>=', $tanggal_awal)
                                                    ->join('penjualan_barang', 'penjualan_barang.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'penjualan_barang.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'penjualan_barang.jumlah AS jumlah', 
                                                            DB::raw('SUM(penjualan_barang.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            }

            return view ('toko.laporan.penjualan.index', compact('laporan_penjualan', 'pembayaran', 'tanggal_awal', 'tanggal_akhir', 'type_pembayaran'));
        } else if (!$tanggal_awal && $tanggal_akhir) {
            if ($type_pembayaran > 0) {
                $laporan_penjualan = PenjualanModel::where('tanggal', '<=', $tanggal_akhir)
                                                    ->where('pembayaran', '=', $type_pembayaran)
                                                    ->join('penjualan_barang', 'penjualan_barang.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'penjualan_barang.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'penjualan_barang.jumlah AS jumlah', 
                                                            DB::raw('SUM(penjualan_barang.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            } else {
                $laporan_penjualan = PenjualanModel::where('tanggal', '<=', $tanggal_akhir)
                                                    ->join('penjualan_barang', 'penjualan_barang.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'penjualan_barang.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'penjualan_barang.jumlah AS jumlah', 
                                                            DB::raw('SUM(penjualan_barang.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            }
    
            return view ('toko.laporan.penjualan.index', compact('laporan_penjualan', 'pembayaran', 'tanggal_awal', 'tanggal_akhir', 'type_pembayaran'));
        } else if ($tanggal_awal && $tanggal_akhir) {
            if ($type_pembayaran > 0) {
                $laporan_penjualan = PenjualanModel::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', $type_pembayaran)
                                                    ->join('penjualan_barang', 'penjualan_barang.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'penjualan_barang.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'penjualan_barang.jumlah AS jumlah', 
                                                            DB::raw('SUM(penjualan_barang.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            } else {
                $laporan_penjualan = PenjualanModel::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->join('penjualan_barang', 'penjualan_barang.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'penjualan_barang.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'penjualan_barang.jumlah AS jumlah', 
                                                            DB::raw('SUM(penjualan_barang.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            }
    
            return view ('toko.laporan.penjualan.index', compact('laporan_penjualan', 'pembayaran', 'tanggal_awal', 'tanggal_akhir', 'type_pembayaran'));
        } else {
            return view ('toko.laporan.penjualan.index', compact('pembayaran'));
        }
    }
}