<?php

namespace App\Http\Controllers\Toko\Transaksi\Persediaan;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersediaanController extends Controller
{
    public function index() {
        $per_barang = BarangModel::select('id', 'kode', 'nama', DB::raw('SUM(stok_etalase) AS stok_etalase'), DB::raw('SUM(stok_gudang) AS stok_gudang'), 'satuan')
                                ->groupBy('nama')
                                ->orderBy('nama')
                                ->get();

        $per_supplier = BarangModel::join('supplier', 'supplier.id', '=', 'barang.id_supplier')
                                ->select('supplier.kode AS kode_supplier', 'supplier.nama AS nama_supplier', 
                                        'barang.*')
                                ->orderBy('barang.nama')
                                ->get();

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

        return view('toko.transaksi.persediaan.index', compact('data_notified', 'data_notif', 'data_notif_hutang', 'per_barang', 'per_supplier'));
    }
}
