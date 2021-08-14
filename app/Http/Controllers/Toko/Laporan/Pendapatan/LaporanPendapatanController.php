<?php

namespace App\Http\Controllers\Toko\Laporan\Pendapatan;

use App\Exports\Toko\Laporan\LaporanPendapatanExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPendapatanController extends Controller
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
            $laporan_pendapatan = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('jurnal.tanggal AS tanggal', 'jurnal.keterangan AS keterangan', 'akun.kode AS kode_akun', 
                                                'akun.nama AS nama_akun', DB::raw('SUM(jurnal.debit) AS debit'), DB::raw('SUM(jurnal.kredit) AS kredit'))
                                                ->whereBetween('jurnal.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                ->where(function($i) {
                                                    $i->where('akun.kode', 'like', '4%')
                                                        ->orWhere('akun.kode', 'like', '5%')
                                                        ->orWhere('akun.kode', 'like', '6%');
                                                })
                                                ->groupBy('akun.kode')
                                                ->get();
                                                
            $pemasukan = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('jurnal.tanggal AS tanggal', 
                                                DB::raw('IFNULL(SUM(jurnal.debit+jurnal.kredit), 0) AS jumlah'))
                                                ->whereBetween('jurnal.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                ->where('akun.kode', 'like', '4%')
                                                ->groupBy('akun.kode')
                                                ->first();
                                                
            $pengeluaran = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('jurnal.tanggal AS tanggal', 
                                                DB::raw('IFNULL(SUM(jurnal.debit+jurnal.kredit), 0) AS jumlah'))
                                                ->whereBetween('jurnal.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                ->where(function($i) {
                                                    $i->where('akun.kode', 'like', '5%')
                                                    ->orWhere('akun.kode', 'like', '6%');
                                                })
                                                ->groupBy('akun.kode')
                                                ->first();
            
            // echo $laporan_pendapatan;

            return view ('toko.laporan.pendapatan.index', compact('cur_date', 'data_notif', 'data_notified', 'data_notif_hutang', 'laporan_pendapatan', 'pemasukan', 'pengeluaran', 'tanggal_awal', 'tanggal_akhir'));
        } else {
            return view ('toko.laporan.pendapatan.index', compact('cur_date', 'data_notif', 'data_notified', 'data_notif_hutang'));
        }
    }

    public function print($tanggal_awal, $tanggal_akhir) {
        if ($tanggal_awal && $tanggal_akhir) {
            $laporan_pendapatan = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('jurnal.tanggal AS tanggal', 'jurnal.keterangan AS keterangan', 'akun.kode AS kode_akun', 
                                                'akun.nama AS nama_akun', DB::raw('SUM(jurnal.debit) AS debit'), DB::raw('SUM(jurnal.kredit) AS kredit'))
                                                ->whereBetween('jurnal.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                ->where(function($i) {
                                                    $i->where('akun.kode', 'like', '4%')
                                                        ->orWhere('akun.kode', 'like', '5%')
                                                        ->orWhere('akun.kode', 'like', '6%');
                                                })
                                                ->groupBy('akun.kode')
                                                ->get();
                                                
            $pemasukan = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('jurnal.tanggal AS tanggal', 
                                                DB::raw('IFNULL(SUM(jurnal.debit+jurnal.kredit), 0) AS jumlah'))
                                                ->whereBetween('jurnal.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                ->where('akun.kode', 'like', '4%')
                                                ->groupBy('jurnal.tanggal')
                                                ->first();
                                                
            $pengeluaran = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('jurnal.tanggal AS tanggal', 
                                                DB::raw('IFNULL(SUM(jurnal.debit+jurnal.kredit), 0) AS jumlah'))
                                                ->whereBetween('jurnal.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                ->where('akun.kode', 'like', '5%')
                                                ->orWhere('akun.kode', 'like', '6%')
                                                ->groupBy('jurnal.tanggal')
                                                ->first();                      
        }

        return view ('toko.laporan.pendapatan.print', compact('laporan_pendapatan', 'pemasukan', 'pengeluaran', 'tanggal_awal', 'tanggal_akhir'));
        
        // $pdf = PDF::loadview('toko.laporan.pembelian.print', ['laporan_pembelian'=>$laporan_pembelian]);
        // return $pdf->download('Laporan Pembelian ' . $pembayaran . $tanggal . '.pdf');
    }

    public function export($tanggal_awal, $tanggal_akhir) {
        return Excel::download(new LaporanPendapatanExport(
                                    $tanggal_awal,
                                    $tanggal_akhir
                                ), 'Laporan Laba Rugi ' . $tanggal_awal . ' - ' . $tanggal_akhir . '.xlsx');
    }
}