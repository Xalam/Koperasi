<?php

namespace App\Http\Controllers\Toko\Transaksi\PesananOnline;

use App\Http\Controllers\Controller;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\PesananOnline\PesananOnlineModel;
use Illuminate\Http\Request;

class PesananOnlineController extends Controller
{
    public function index() {
        $pesanan_online = PesananOnlineModel::join('tb_anggota', 'tb_anggota.id', '=', 'pesanan_online.id_anggota')
                                                ->select('pesanan_online.*', 'tb_anggota.kd_anggota AS kode_anggota',
                                                            'tb_anggota.nama_anggota AS nama_anggota')
                                                ->get();

        return view('toko.transaksi.pesanan_online.index', compact('pesanan_online'));
    }

    public function proses($id, $proses) {
        PesananOnlineModel::where('id', $id)->update([
            'proses' => $proses
        ]);

        return response()->json(['code' => 200]);
    }
}
