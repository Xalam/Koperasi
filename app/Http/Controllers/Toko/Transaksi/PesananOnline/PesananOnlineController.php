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

        HutangModel::where(DB::raw('DATE_ADD(DATE(NOW()), INTERVAL 3 DAY)'), '>=', DB::raw('DATE(jatuh_tempo)'))->update([
            'alert_status' => 1
        ]);

        $data_notif_hutang = HutangModel::join('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                    ->select('hutang.*', 'supplier.nama AS nama_supplier')
                                    ->get();
        
        $pesanan_online = PesananOnlineModel::join('tb_anggota', 'tb_anggota.id', '=', 'pesanan_online.id_anggota')
                                                ->select('pesanan_online.*', 'tb_anggota.kd_anggota AS kode_anggota',
                                                            'tb_anggota.nama_anggota AS nama_anggota')
                                                ->where('pesanan_online.pickup', 0)
                                                ->orderBy('pesanan_online.id', 'desc')
                                                ->get();
        
        $pickup_pesanan = PesananOnlineModel::join('tb_anggota', 'tb_anggota.id', '=', 'pesanan_online.id_anggota')
                                                ->select('pesanan_online.*', 'tb_anggota.kd_anggota AS kode_anggota',
                                                            'tb_anggota.nama_anggota AS nama_anggota')
                                                ->where('pesanan_online.pickup', 1)
                                                ->orderBy('pesanan_online.id', 'desc')
                                                ->get();

        return view('toko.transaksi.pesanan_online.index', compact('data_notified', 'data_notif', 'data_notif_hutang', 'pesanan_online', 'pickup_pesanan'));
    }

    public function proses($id, $proses) {
        PesananOnlineModel::where('id', $id)->update([
            'proses' => $proses
        ]);

        return response()->json(['code' => 200]);
    }

    public function pickup(Request $request) {
        PesananOnlineModel::where('id', $request->id)->update([
            'pickup' => 1
        ]);

        return response()->json(['code' => 200]);
    }

    public function batalPickup(Request $request) {
        PesananOnlineModel::where('id', $request->id)->update([
            'pickup' => 0
        ]);

        return response()->json(['code' => 200]);
    }

    public function delete(Request $request) {
        $data_pesanan = PesananOnlineModel::where('id', $request->id)->first();
        $data_penjualan = PenjualanModel::where('nomor', $data_pesanan->nomor)->first();
        $data_barang = PenjualanBarangModel::where('nomor', $data_pesanan->nomor)->get();

        foreach ($data_barang as $data) {
            $barang = BarangModel::where('id', $data->id_barang)->first();

            if ($barang) {
                BarangModel::where('id', $data->id_barang)->update([
                    'stok_etalase' => $barang->stok_etalase + $data->jumlah
                ]);
            }
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
        // JurnalUmum::where('kode_jurnal', 'J' . $data_pesanan->nomor)->delete();

        return response()->json(['code' => 200]);
    }

    public function nota($nomor) {
        $pembeli = PenjualanModel::leftJoin('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                    ->select(DB::raw('IFNULL(tb_anggota.nama_anggota, "Masyarakat Umum") AS nama_anggota'), 'penjualan.*')
                                    ->orderBy('penjualan.id', 'desc')
                                    ->where('nomor', $nomor)
                                    ->get();

        $pesanan_online = PenjualanBarangModel::join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                            ->select('barang.nama AS nama_barang', 'barang.harga_jual AS harga_jual',
                                                    'detail_jual.jumlah AS jumlah', 'detail_jual.total_harga AS total_harga')
                                            ->orderBy('detail_jual.id', 'desc')
                                            ->where('nomor', $nomor)->get();

        return view('toko.transaksi.pesanan_online.nota', compact('pembeli', 'pesanan_online'));
    }
}
