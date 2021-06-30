<?php

namespace App\Http\Controllers\Toko\Laporan\Pendapatan;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPendapatanController extends Controller
{
    public function index(Request $request) {
        $cur_date = Carbon::now();

        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            }
        }

        $tanggal = $request->input('tanggal');
        
        if ($tanggal) {
            $laporan_pendapatan = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('jurnal.tanggal AS tanggal', 'jurnal.keterangan AS keterangan', 'akun.kode AS kode_akun', 
                                                'akun.nama AS nama_akun', DB::raw('SUM(jurnal.debit) AS debit'), DB::raw('SUM(jurnal.kredit) AS kredit'))
                                                ->where('jurnal.tanggal', $tanggal)
                                                ->where('akun.kode', 'like', '4%')
                                                ->orWhere('akun.kode', 'like', '5%')
                                                ->orWhere('akun.kode', 'like', '6%')
                                                ->groupBy('akun.kode')
                                                ->get();
                                                
            $pemasukan = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('jurnal.tanggal AS tanggal', 
                                                DB::raw('IFNULL(SUM(jurnal.debit+jurnal.kredit), 0) AS jumlah'))
                                                ->where('jurnal.tanggal', $tanggal)
                                                ->where('akun.kode', 'like', '4%')
                                                ->groupBy('jurnal.tanggal')
                                                ->first()->jumlah;
                                                
            $pengeluaran = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('jurnal.tanggal AS tanggal', 
                                                DB::raw('IFNULL(SUM(jurnal.debit+jurnal.kredit), 0) AS jumlah'))
                                                ->where('jurnal.tanggal', $tanggal)
                                                ->where('akun.kode', 'like', '5%')
                                                ->orWhere('akun.kode', 'like', '6%')
                                                ->groupBy('jurnal.tanggal')
                                                ->first()->jumlah;
                                                
            return view ('toko.laporan.pendapatan.index', compact('cur_date', 'data_notif', 'data_notified', 'laporan_pendapatan', 'pemasukan', 'pengeluaran', 'tanggal'));
        } else {
            return view ('toko.laporan.pendapatan.index', compact('cur_date', 'data_notif', 'data_notified'));
        }
    }

    public function print($tanggal) {
        if ($tanggal) {
            $laporan_pendapatan = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                                ->select('jurnal.tanggal AS tanggal', 'akun.kode AS kode_akun', 
                                                'akun.nama AS nama_akun', 'jurnal.debit+jurnal.kredit AS jumlah')
                                                ->where('jurnal.tanggal', $tanggal)
                                                ->get();                            
        }

        return view ('toko.laporan.pendapatan.print', compact('laporan_pendapatan', 'tanggal'));
        
        // $pdf = PDF::loadview('toko.laporan.pembelian.print', ['laporan_pembelian'=>$laporan_pembelian]);
        // return $pdf->download('Laporan Pembelian ' . $pembayaran . $tanggal . '.pdf');
    }

    public function export($tanggal) {
        return Excel::download(new JurnalModel(
                                    $tanggal
                                ), 'Laporan Retur Pembelian ' . $tanggal . '.xlsx');
    }
}