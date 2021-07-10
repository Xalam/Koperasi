<?php

namespace App\Http\Controllers\Toko\Laporan\Persediaan;

use App\Exports\Toko\Laporan\LaporanPersediaanExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPersediaanController extends Controller
{
    public function index(Request $request) {
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

        $jumlah_barang = $request->input('jumlah_barang');

        if ($jumlah_barang) {
            $laporan_persediaan = BarangModel::where('stok', '<', $jumlah_barang)->get();

            return view ('toko.laporan.persediaan.index', compact('laporan_persediaan', 'data_notified', 'data_notif', 'jumlah_barang'));
        }

        return view ('toko.laporan.persediaan.index', compact('data_notified', 'data_notif'));
    }

    public function print($minimal_stok) {
        $laporan_persediaan = BarangModel::where('stok', '<', $minimal_stok)->get();

        return view ('toko.laporan.persediaan.print', compact('laporan_persediaan'));
        
        // $pdf = PDF::loadview('toko.laporan.pembelian.print', ['laporan_pembelian'=>$laporan_pembelian]);
        // return $pdf->download('Laporan Pembelian ' . $pembayaran . $tanggal . '.pdf');
    }

    public function export($minimal_stok) {
        return Excel::download(new LaporanPersediaanExport(
                                    $minimal_stok
                                ), 'Laporan Persediaan Barang Kurang Dari ' . $minimal_stok . '.xlsx');
    }
}
