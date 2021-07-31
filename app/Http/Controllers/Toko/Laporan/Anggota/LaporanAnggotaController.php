<?php

namespace App\Http\Controllers\Toko\Laporan\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Toko\Master\Barang\BarangModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanAnggotaController extends Controller
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

        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        if ($tanggal_awal && $tanggal_akhir) {
            $laporan_anggota = Anggota::join('penjualan', 'penjualan.id_anggota', '=', 'tb_anggota.id')
                                            ->select('tb_anggota.id AS id', 'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.nama_anggota AS nama_anggota', 
                                                    DB::raw('SUM(penjualan.jumlah_harga) AS total_belanja'))
                                            ->groupBy('penjualan.id_anggota')
                                            ->whereBetween('penjualan.tanggal', [$tanggal_awal, $tanggal_akhir])
                                            ->orderBy('tb_anggota.nama_anggota')
                                            ->get();

            return view ('toko.laporan.anggota.index', compact('cur_date', 'laporan_anggota', 'data_notified', 'data_notif', 'tanggal_awal', 'tanggal_akhir'));
        } else {
            return view ('toko.laporan.anggota.index', compact('cur_date', 'data_notified', 'data_notif'));
        }
    }
    
    public function detail($id, $tanggal_awal, $tanggal_akhir) {
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

        if ($tanggal_awal && $tanggal_akhir) {
            $laporan_detail_anggota = Anggota::join('penjualan', 'penjualan.id_anggota', '=', 'tb_anggota.id')
                                            ->select('tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.nama_anggota AS nama_anggota', 
                                                    'penjualan.*')
                                            ->where('penjualan.id_anggota', $id)
                                            ->whereBetween('penjualan.tanggal', [$tanggal_awal, $tanggal_akhir])
                                            ->orderBy('tb_anggota.nama_anggota')
                                            ->get();

            return view ('toko.laporan.anggota.detail', compact('cur_date', 'laporan_detail_anggota', 'data_notified', 'data_notif', 'id', 'tanggal_awal', 'tanggal_akhir'));
        } else {
            return view ('toko.laporan.anggota.detail', compact('cur_date', 'data_notified', 'data_notif'));
        }
    }
}
