<?php

namespace App\Http\Controllers\Toko\Laporan\Retur;

use App\Exports\Toko\Laporan\LaporanReturPembelianExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Retur\ReturPembelianModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanReturPembelianController extends Controller
{
    public function index(Request $request) {
        $cur_date = Carbon::now();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            } else {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 0
                ]);
            }
        }

        $data_notif = BarangModel::where('alert_status', 1)->get();

        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        
        if ($tanggal_awal && $tanggal_akhir) {
            $laporan_retur_pembelian = ReturPembelianModel::whereBetween('retur.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                        ->join('detail_retur', 'detail_retur.nomor', '=', 'retur.nomor')
                                                        ->join('pembelian', 'pembelian.id', '=', 'retur.id_beli')
                                                        ->join('barang', 'barang.id', '=', 'detail_retur.id_barang')
                                                        ->select('retur.nomor AS nomor', 'retur.tanggal', 'pembelian.nomor AS nomor_beli',
                                                                'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                                                'barang.hpp AS hpp', 'detail_retur.jumlah AS jumlah', 
                                                                'detail_retur.total_harga AS total_harga')
                                                        ->get();
                                                
            return view ('toko.laporan.retur.index', compact('cur_date', 'laporan_retur_pembelian', 'data_notified', 'data_notif', 'tanggal_awal', 'tanggal_akhir'));
        } else {
            return view ('toko.laporan.retur.index', compact('cur_date', 'data_notified', 'data_notif'));
        }
    }

    public function print($tanggal_awal, $tanggal_akhir) {
        if ($tanggal_awal && $tanggal_akhir) {
            $laporan_retur_pembelian = ReturPembelianModel::whereBetween('retur.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                        ->join('detail_retur', 'detail_retur.nomor', '=', 'retur.nomor')
                                                        ->join('pembelian', 'pembelian.id', '=', 'retur.id_beli')
                                                        ->join('barang', 'barang.id', '=', 'detail_retur.id_barang')
                                                        ->select('retur.nomor AS nomor', 'retur.tanggal', 'pembelian.nomor AS nomor_beli',
                                                                'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                                                'barang.hpp AS hpp', 'detail_retur.jumlah AS jumlah', 
                                                                'detail_retur.total_harga AS total_harga')
                                                        ->get();
        }

        return view ('toko.laporan.retur.print', compact('laporan_retur_pembelian', 'tanggal_awal', 'tanggal_akhir'));
        
        // $pdf = PDF::loadview('toko.laporan.pembelian.print', ['laporan_pembelian'=>$laporan_pembelian]);
        // return $pdf->download('Laporan Pembelian ' . $pembayaran . $tanggal . '.pdf');
    }

    public function export($tanggal_awal, $tanggal_akhir) {
        return Excel::download(new LaporanReturPembelianExport(
                                    $tanggal_awal, 
                                    $tanggal_akhir
                                ), 'Laporan Retur Pembelian ' . $tanggal_awal . ' Sampai ' . $tanggal_akhir . '.xlsx');
    }
}
