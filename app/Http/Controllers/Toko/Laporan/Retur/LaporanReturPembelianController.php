<?php

namespace App\Http\Controllers\Toko\Laporan\Retur;

use App\Http\Controllers\Controller;
use App\Models\Toko\Transaksi\Retur\ReturPembelianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanReturPembelianController extends Controller
{
    public function index(Request $request) {
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        
        if ($tanggal_awal && !$tanggal_akhir) {
            $laporan_retur_pembelian = ReturPembelianModel::where('retur.tanggal', '>=', $tanggal_awal)
                                                ->join('detail_retur', 'detail_retur.nomor', '=', 'retur.nomor')
                                                ->join('pembelian', 'pembelian.id', '=', 'retur.id_beli')
                                                ->join('barang', 'barang.id', '=', 'detail_retur.id_barang')
                                                ->select('retur.tanggal', 'retur.nomor AS nomor', 'pembelian.nomor AS nomor_beli',
                                                        'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                                        DB::raw('SUM(detail_retur.jumlah) AS jumlah'), 'barang.hpp AS hpp',
                                                        DB::raw('SUM(detail_retur.total_harga) AS total_harga'))
                                                ->groupBy('barang.kode')
                                                ->paginate(10);

            return view ('toko.laporan.retur.index', compact('laporan_retur_pembelian', 'tanggal_awal', 'tanggal_akhir'));
        } else if (!$tanggal_awal && $tanggal_akhir) {
            $laporan_retur_pembelian = ReturPembelianModel::where('retur.tanggal', '<=', $tanggal_awal)
                                                ->join('detail_retur', 'detail_retur.nomor', '=', 'retur.nomor')
                                                ->join('pembelian', 'pembelian.id', '=', 'retur.id_beli')
                                                ->join('barang', 'barang.id', '=', 'detail_retur.id_barang')
                                                ->select('retur.tanggal', 'retur.nomor AS nomor', 'pembelian.nomor AS nomor_beli',
                                                        'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                                        DB::raw('SUM(detail_retur.jumlah) AS jumlah'), 'barang.hpp AS hpp',
                                                        DB::raw('SUM(detail_retur.total_harga) AS total_harga'))
                                                ->groupBy('barang.kode')
                                                ->paginate(10);
                                                
            return view ('toko.laporan.retur.index', compact('laporan_retur_pembelian', 'tanggal_awal', 'tanggal_akhir'));
        } else if ($tanggal_awal && $tanggal_akhir) {
            $laporan_retur_pembelian = ReturPembelianModel::whereBetween('retur.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                ->join('detail_retur', 'detail_retur.nomor', '=', 'retur.nomor')
                                                ->join('pembelian', 'pembelian.id', '=', 'retur.id_beli')
                                                ->join('barang', 'barang.id', '=', 'detail_retur.id_barang')
                                                ->select('retur.tanggal', 'retur.nomor AS nomor', 'pembelian.nomor AS nomor_beli',
                                                        'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                                        DB::raw('SUM(detail_retur.jumlah) AS jumlah'), 'barang.hpp AS hpp',
                                                        DB::raw('SUM(detail_retur.total_harga) AS total_harga'))
                                                ->groupBy('barang.kode')
                                                ->paginate(10);
                                                
                                                return view ('toko.laporan.retur.index', compact('laporan_retur_pembelian', 'tanggal_awal', 'tanggal_akhir'));
        } else {
            return view ('toko.laporan.retur.index');
        }
    }
}
