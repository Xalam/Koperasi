<?php

namespace App\Http\Controllers\Toko\Laporan\Persediaan;

use App\Exports\Toko\Laporan\LaporanPersediaanExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPersediaanController extends Controller
{
    public function index(Request $request) {
        $cur_date = Carbon::now();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok_etalase <= $data->stok_minimal || $data->stok_gudang <= $data->stok_minimal) {
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
            $laporan_persediaan = BarangModel::select('id', 'kode', 'nama', 'hpp', DB::raw('SUM(stok_etalase + stok_gudang) AS stok'), 'satuan')
                                    ->groupBy('nama')
                                    ->orderBy('nama')
                                    ->having(DB::raw('SUM(stok_etalase + stok_gudang)'), '<', $jumlah_barang)
                                    ->get();

            return view ('toko.laporan.persediaan.index', compact('cur_date', 'laporan_persediaan', 'data_notified', 'data_notif', 'jumlah_barang'));
        }

        return view ('toko.laporan.persediaan.index', compact('cur_date', 'data_notified', 'data_notif'));
    }

    public function print($minimal_stok) {
        $laporan_persediaan = BarangModel::where(DB::raw('SUM(stok_etalase + stok_gudang)'), '<', $minimal_stok)->get();

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
