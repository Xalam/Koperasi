<?php

namespace App\Http\Controllers\Toko\Transaksi\PesananOnline;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Toko\Master\Akun\AkunModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanBarangModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use App\Models\Toko\Transaksi\PesananOnline\PesananOnlineModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananOnlineController extends Controller
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

    public function delete(Request $request) {
        $data_pesanan = PesananOnlineModel::where('id', $request->id)->first();
        $data_penjualan = PenjualanModel::where('nomor', $data_pesanan->nomor)->first();
        $data_barang = PenjualanBarangModel::where('nomor', $data_pesanan->nomor)->get();

        foreach ($data_barang as $data) {
            $barang = BarangModel::where('id', $data->id_barang)->first();

            BarangModel::where('id', $data->id_barang)->update([
                'stok_etalase' => $barang->stok_etalase + $data->jumlah
            ]);
        }

        $kodePiutang = 1122;
        $kodePendapatan = 4102;
        $kodePersediaan = 1131;
        $kodeHpp = 5101;
        $kodeKas = 1102;

        if ($data_penjualan->pembayaran == 1) {
            AkunModel::where('kode', $kodePiutang)->decrement('debit', $data_pesanan->jumlah_harga);

            AkunModel::where('kode', $kodePendapatan)->decrement('kredit', $data_pesanan->jumlah_harga);
            
            AkunModel::where('kode', $kodePersediaan)->increment('debit', $data_pesanan->jumlah_harga);
            
            AkunModel::where('kode', $kodeHpp)->decrement('debit', $data_pesanan->jumlah_harga);
        } else {
            AkunModel::where('kode', $kodeKas)->decrement('debit', $data_pesanan->jumlah_harga);

            AkunModel::where('kode', $kodePendapatan)->decrement('kredit', $data_pesanan->jumlah_harga);
            
            AkunModel::where('kode', $kodePersediaan)->increment('debit', $data_pesanan->jumlah_harga);
            
            AkunModel::where('kode', $kodeHpp)->decrement('debit', $data_pesanan->jumlah_harga);
        }

        PesananOnlineModel::where('id', $request->id)->delete();
        PenjualanModel::where('nomor', $data_pesanan->nomor)->delete();
        PenjualanBarangModel::where('nomor', $data_pesanan->nomor)->delete();
        JurnalModel::where('nomor', 'J' . $data_pesanan->nomor)->delete();
        JurnalUmum::where('kode_jurnal', 'J' . $data_pesanan->nomor)->delete();

        return response()->json(['code' => 200]);
    }
}
