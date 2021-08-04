<?php

namespace App\Http\Controllers\Toko\Laporan\Master;

use App\Exports\Toko\Laporan\LaporanMasterExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Admin\AdminModel;
use App\Models\Toko\Master\Anggota\AnggotaModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanMasterController extends Controller
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

        $bagian = $request->input('bagian');

        if ($bagian) {
            if ($bagian == "Admin") {
                $laporan_master = AdminModel::all();
            } else if ($bagian == "Barang") {
                $laporan_master = BarangModel::all();
            } else if ($bagian == "Anggota") {
                $laporan_master = AnggotaModel::all();
            } else {
                $laporan_master = SupplierModel::all();
            }

            return view ('toko.laporan.master.index', compact('bagian', 'cur_date', 'data_notified', 'data_notif', 'data_notif_hutang', 'laporan_master'));
        } else {
            return view ('toko.laporan.master.index', compact('cur_date', 'data_notified', 'data_notif', 'data_notif_hutang'));
        }
    }

    public function print($bagian) {
        if ($bagian == "Admin") {
            $laporan_master = AdminModel::get();
        } else if ($bagian == "Barang") {
            $laporan_master = BarangModel::get();
        } else if ($bagian == "Anggota") {
            $laporan_master = AnggotaModel::get();
        } else {
            $laporan_master = SupplierModel::get();
        }

        return view ('toko.laporan.master.print', compact('laporan_master', 'bagian'));
        
        // $pdf = PDF::loadview('toko.laporan.pembelian.print', ['laporan_pembelian'=>$laporan_pembelian]);
        // return $pdf->download('Laporan Pembelian ' . $pembayaran . $tanggal . '.pdf');
    }

    public function export($bagian) {
        return Excel::download(new LaporanMasterExport(
                                    $bagian
                                ), 'Laporan Master ' . $bagian . '.xlsx');
    }
}