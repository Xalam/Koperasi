<?php

namespace App\Http\Controllers\Toko\Laporan\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\Toko\Transaksi\PembayaranModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanPembelianController extends Controller
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
                $laporan_pembelian = PembelianModel::where('pembelian.tanggal', '>=', $tanggal_awal)
                                                    ->where('pembayaran', '=', $type_pembayaran)
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', DB::raw('SUM(detail_beli.jumlah) AS jumlah'), 
                                                            DB::raw('SUM(detail_beli.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            } else {
                $laporan_pembelian = PembelianModel::where('pembelian.tanggal', '>=', $tanggal_awal)
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', DB::raw('SUM(detail_beli.jumlah) AS jumlah'), 
                                                            DB::raw('SUM(detail_beli.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            }

            return view ('toko.laporan.pembelian.index', compact('laporan_pembelian', 'pembayaran', 'tanggal_awal', 'tanggal_akhir', 'type_pembayaran'));
        } else if (!$tanggal_awal && $tanggal_akhir) {
            if ($type_pembayaran > 0) {
                $laporan_pembelian = PembelianModel::where('pembelian.tanggal', '<=', $tanggal_akhir)
                                                    ->where('pembayaran', '=', $type_pembayaran)
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', DB::raw('SUM(detail_beli.jumlah) AS jumlah'), 
                                                            DB::raw('SUM(detail_beli.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            } else {
                $laporan_pembelian = PembelianModel::where('pembelian.tanggal', '<=', $tanggal_akhir)
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', DB::raw('SUM(detail_beli.jumlah) AS jumlah'), 
                                                            DB::raw('SUM(detail_beli.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            }
    
            return view ('toko.laporan.pembelian.index', compact('laporan_pembelian', 'pembayaran', 'tanggal_awal', 'tanggal_akhir', 'type_pembayaran'));
        } else if ($tanggal_awal && $tanggal_akhir) {
            if ($type_pembayaran > 0) {
                $laporan_pembelian = PembelianModel::whereBetween('pembelian.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', $type_pembayaran)
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', DB::raw('SUM(detail_beli.jumlah) AS jumlah'), 
                                                            DB::raw('SUM(detail_beli.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            } else {
                $laporan_pembelian = PembelianModel::whereBetween('pembelian.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', DB::raw('SUM(detail_beli.jumlah) AS jumlah'), 
                                                            DB::raw('SUM(detail_beli.total_harga) AS total_harga'))
                                                    ->groupBy('barang.kode')
                                                    ->paginate(10);
            }
    
            return view ('toko.laporan.pembelian.index', compact('laporan_pembelian', 'pembayaran', 'tanggal_awal', 'tanggal_akhir', 'type_pembayaran'));
        } else {
            return view ('toko.laporan.pembelian.index', compact('pembayaran'));
        }
    }
}
