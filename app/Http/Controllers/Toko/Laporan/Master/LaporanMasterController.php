<?php

namespace App\Http\Controllers\Toko\Laporan\Master;

use App\Exports\Toko\Laporan\LaporanMasterExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Admin\AdminModel;
use App\Models\Toko\Master\Anggota\AnggotaModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanMasterController extends Controller
{
    public function index(Request $request) {
        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            }
        }

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

            return view ('toko.laporan.master.index', compact('bagian', 'data_notified', 'data_notif', 'laporan_master'));
        } else {
            return view ('toko.laporan.master.index', compact('data_notified', 'data_notif'));
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