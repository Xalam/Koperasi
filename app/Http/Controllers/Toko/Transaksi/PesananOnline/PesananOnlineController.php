<?php

namespace App\Http\Controllers\Toko\Transaksi\PesananOnline;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\PesananOnline\PesananOnlineModel;
use Illuminate\Http\Request;

class PesananOnlineController extends Controller
{
    public function index() {
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
        
        $pesanan_online = PesananOnlineModel::join('tb_anggota', 'tb_anggota.id', '=', 'pesanan_online.id_anggota')
                                                ->select('pesanan_online.*', 'tb_anggota.kd_anggota AS kode_anggota',
                                                            'tb_anggota.nama_anggota AS nama_anggota')
                                                ->get();

        return view('toko.transaksi.pesanan_online.index', compact('data_notified', 'data_notif', 'pesanan_online'));
    }

    public function proses($id, $proses) {
        PesananOnlineModel::where('id', $id)->update([
            'proses' => $proses
        ]);

        return response()->json(['code' => 200]);
    }
}
