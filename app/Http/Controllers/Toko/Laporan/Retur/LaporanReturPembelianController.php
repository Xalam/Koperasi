<?php

namespace App\Http\Controllers\Toko\Laporan\Retur;

use App\Exports\Toko\Laporan\LaporanReturPembelianExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Retur\ReturPembelianBarangModel;
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

        $tanggal = $request->input('tanggal');
        
        if ($tanggal) {
            $laporan_retur_pembelian = ReturPembelianModel::where('retur.tanggal', $tanggal)
                                                        ->join('detail_retur', 'detail_retur.nomor', '=', 'retur.nomor')
                                                        ->join('pembelian', 'pembelian.id', '=', 'retur.id_beli')
                                                        ->join('barang', 'barang.id', '=', 'detail_retur.id_barang')
                                                        ->select('retur.nomor AS nomor', 'retur.tanggal', 'pembelian.nomor AS nomor_beli',
                                                                'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                                                'barang.hpp AS hpp', 'detail_retur.jumlah AS jumlah', 
                                                                'detail_retur.total_harga AS total_harga')
                                                        ->get();
                                                
            return view ('toko.laporan.retur.index', compact('cur_date', 'laporan_retur_pembelian', 'data_notified', 'data_notif', 'tanggal'));
        } else {
            return view ('toko.laporan.retur.index', compact('cur_date', 'data_notified', 'data_notif', 'tanggal'));
        }
    }

    public function print($tanggal) {
        if ($tanggal) {
            $laporan_retur_pembelian = ReturPembelianModel::where('retur.tanggal', $tanggal)
                                                        ->join('detail_retur', 'detail_retur.nomor', '=', 'retur.nomor')
                                                        ->join('pembelian', 'pembelian.id', '=', 'retur.id_beli')
                                                        ->join('barang', 'barang.id', '=', 'detail_retur.id_barang')
                                                        ->select('retur.nomor AS nomor', 'retur.tanggal', 'pembelian.nomor AS nomor_beli',
                                                                'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                                                'barang.hpp AS hpp', 'detail_retur.jumlah AS jumlah', 
                                                                'detail_retur.total_harga AS total_harga')
                                                        ->get();
        }

        return view ('toko.laporan.retur.print', compact('laporan_retur_pembelian', 'tanggal'));
        
        // $pdf = PDF::loadview('toko.laporan.pembelian.print', ['laporan_pembelian'=>$laporan_pembelian]);
        // return $pdf->download('Laporan Pembelian ' . $pembayaran . $tanggal . '.pdf');
    }

    public function nota($nomor) {
        $supplier = ReturPembelianModel::leftJoin('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                    ->select('supplier.kode AS kode_supplier', 'supplier.nama AS nama_supplier', 
                                            'supplier.alamat AS alamat_supplier', 'pembelian.*')
                                    ->where('nomor', $nomor)
                                    ->limit(1)
                                    ->get();

        $pembelian = ReturPembelianBarangModel::join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                            ->select('barang.nama AS nama_barang', 'barang.kode AS kode_barang', 'barang.satuan AS satuan', 
                                                    'detail_beli.jumlah AS jumlah', 'detail_beli.harga_satuan AS harga_satuan', 
                                                    'detail_beli.total_harga AS total_harga')
                                            ->where('nomor', $nomor)->get();

        return view('toko.laporan.retur.nota', compact('supplier', 'pembelian'));
    }

    public function export($tanggal) {
        return Excel::download(new LaporanReturPembelianExport(
                                    $tanggal 
                                ), 'Laporan Retur Pembelian ' . $tanggal . '.xlsx');
    }
}
