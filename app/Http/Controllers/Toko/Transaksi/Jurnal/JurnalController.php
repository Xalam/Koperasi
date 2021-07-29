<?php

namespace App\Http\Controllers\Toko\Transaksi\Jurnal;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index() {
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

        $jurnal = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                            ->select('jurnal.*', 'akun.kode AS kode_akun', 'akun.nama AS nama_akun')
                            ->orderBy('jurnal.created_at', 'asc')
                            ->get();

        return view('toko.transaksi.jurnal.index', compact('data_notified', 'data_notif', 'jurnal'));
    }
}
