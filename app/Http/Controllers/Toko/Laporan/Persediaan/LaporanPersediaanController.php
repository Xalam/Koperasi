<?php

namespace App\Http\Controllers\Toko\Laporan\Persediaan;

use App\Exports\Toko\Laporan\LaporanPersediaanExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
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

        HutangModel::where(DB::raw('DATE_ADD(DATE(NOW()), INTERVAL 3 DAY)'), '>=', DB::raw('DATE(jatuh_tempo)'))->update([
            'alert_status' => 1
        ]);

        $data_notif_hutang = HutangModel::join('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                    ->select('hutang.*', 'supplier.nama AS nama_supplier')
                                    ->get();

        $jumlah_barang = $request->input('jumlah_barang');

        if ($jumlah_barang) {
            $laporan_persediaan = BarangModel::select('id', 'kode', 'nama', 'hpp', DB::raw('(stok_etalase + stok_gudang) AS stok'), 'satuan')
                                    ->groupBy('nama')
                                    ->orderBy('nama')
                                    ->having('stok', '<', $jumlah_barang)
                                    ->get();

            return view ('toko.laporan.persediaan.index', compact('cur_date', 'laporan_persediaan', 'data_notified', 'data_notif', 'data_notif_hutang', 'jumlah_barang'));
        }

        return view ('toko.laporan.persediaan.index', compact('cur_date', 'data_notified', 'data_notif', 'data_notif_hutang'));
    }

    public function minimalPersediaan() {
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

        HutangModel::where(DB::raw('DATE_ADD(DATE(NOW()), INTERVAL 3 DAY)'), '>=', DB::raw('DATE(jatuh_tempo)'))->update([
            'alert_status' => 1
        ]);

        $data_notif_hutang = HutangModel::join('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                    ->select('hutang.*', 'supplier.nama AS nama_supplier')
                                    ->get();
        
        $laporan_minimal_persediaan = BarangModel::all();

        return view ('toko.laporan.minimal_persediaan.index', compact('cur_date', 'data_notified', 'data_notif', 'data_notif_hutang', 'laporan_minimal_persediaan'));
    }

    public function print($minimal_stok) {
        $laporan_persediaan = BarangModel::select('id', 'kode', 'nama', 'hpp', DB::raw('(stok_etalase + stok_gudang) AS stok'), 'satuan')
                                ->groupBy('nama')
                                ->orderBy('nama')
                                ->having('stok', '<', $minimal_stok)
                                ->get();

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
