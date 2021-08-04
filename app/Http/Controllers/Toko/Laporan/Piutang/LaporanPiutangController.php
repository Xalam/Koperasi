<?php

namespace App\Http\Controllers\Toko\Laporan\Piutang;

use App\Exports\Toko\Laporan\LaporanPiutangExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Piutang\PiutangModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPiutangController extends Controller
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

        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        if ($tanggal_awal && $tanggal_akhir) {
            $laporan_piutang = PiutangModel::whereBetween(DB::raw('date(piutang.created_at)'), [$tanggal_awal, $tanggal_akhir])
                                                ->join('tb_anggota', 'tb_anggota.id', '=', 'piutang.id_anggota')
                                                ->select('piutang.*', 'tb_anggota.nama_anggota AS nama_anggota', 
                                                        'tb_anggota.kd_anggota AS kode_anggota')
                                                ->get();
    
            return view ('toko.laporan.piutang.index', compact('cur_date', 'laporan_piutang', 'data_notified', 'data_notif', 'data_notif_hutang', 'tanggal_awal', 'tanggal_akhir'));
        } else {
            return view ('toko.laporan.piutang.index', compact('cur_date', 'data_notified', 'data_notif', 'data_notif_hutang'));
        }
    }

    public function print($tanggal_awal, $tanggal_akhir) {
        if ($tanggal_awal != $tanggal_akhir) {
            $tanggal = ' ' . $tanggal_awal . ' Sampai ' . $tanggal_akhir;
        } else {
            $tanggal = ' ' . $tanggal_awal;
        }

        $laporan_penjualan = '';

        if ($tanggal_awal && $tanggal_akhir) {
            $laporan_piutang = PiutangModel::whereBetween(DB::raw('date(piutang.created_at)'), [$tanggal_awal, $tanggal_akhir])
                                                ->join('tb_anggota', 'tb_anggota.id', '=', 'piutang.id_anggota')
                                                ->select('piutang.*', 'tb_anggota.nama_anggota AS nama_anggota', 
                                                        'tb_anggota.kd_anggota AS kode_anggota')
                                                ->get();
        }
    
        return view ('toko.laporan.piutang.print', compact('laporan_piutang'));
    }

    public function export($tanggal_awal, $tanggal_akhir) {
        if ($tanggal_awal != $tanggal_akhir) {
            $tanggal = ' ' . $tanggal_awal . ' Sampai ' . $tanggal_akhir;
        } else {
            $tanggal = ' ' . $tanggal_awal;
        }

        return Excel::download(new LaporanPiutangExport(
                                    $tanggal_awal, 
                                    $tanggal_akhir,
                                ), 'Laporan Piutang ' . $tanggal . '.xlsx');
    }
}