<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\ChartModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanBarangModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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

        HutangModel::where(DB::raw('DATE_ADD(DATE(NOW()), INTERVAL 3 DAY)'), '>=', DB::raw('DATE(jatuh_tempo)'))->update([
            'alert_status' => 1
        ]);

        $data_notif_hutang = HutangModel::join('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                    ->select('hutang.*', 'supplier.nama AS nama_supplier')
                                    ->get();

        $year = Carbon::now()->year;
        
        $total_penjualan = PenjualanModel::select(DB::raw("COUNT(*) AS total"))->where(DB::raw('YEAR(created_at)'), now()->year)->first();
        $total_pembelian = PembelianModel::select(DB::raw("COUNT(*) AS total"))->where(DB::raw('YEAR(created_at)'), now()->year)->first();
     
        $pemasukan = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                    ->select('jurnal.tanggal AS tanggal', 
                                    DB::raw('IFNULL(SUM(jurnal.debit+jurnal.kredit), 0) AS jumlah'))
                                    ->where('akun.kode', 'like', '4%')
                                    ->where(DB::raw('YEAR(jurnal.created_at)'), now()->year)
                                    ->first();
        
        $pengeluaran = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                    ->select('jurnal.tanggal AS tanggal', 
                                    DB::raw('IFNULL(SUM(jurnal.debit+jurnal.kredit), 0) AS jumlah'))
                                    ->where(DB::raw('YEAR(jurnal.created_at)'), now()->year)
                                    ->where(function($i) {
                                        $i->where('akun.kode', 'like', '5%')
                                        ->orWhere('akun.kode', 'like', '6%');
                                    })
                                    ->where(DB::raw('YEAR(jurnal.created_at)'), now()->year)
                                    ->first();

        $total_pendapatan = $pemasukan->jumlah - $pengeluaran->jumlah;
            
        // Chart
        $list_bulan = [];
        $list_bulan[] = 'Januari';
        $list_bulan[] = 'Februari';
        $list_bulan[] = 'Maret';
        $list_bulan[] = 'April';
        $list_bulan[] = 'Mei';
        $list_bulan[] = 'Juni';
        $list_bulan[] = 'Juli';
        $list_bulan[] = 'Agustus';
        $list_bulan[] = 'September';
        $list_bulan[] = 'Oktober';
        $list_bulan[] = 'November';
        $list_bulan[] = 'Desember';
        
        $listPenjualan = PenjualanBarangModel::select(DB::raw("SUM(total_harga) AS jumlah"), DB::raw('MONTH(created_at) AS tanggal'))
                                                ->where(DB::raw('YEAR(created_at)'), now()->year)
                                                ->groupBy(DB::raw('MONTH(created_at)'))
                                                ->get();

        foreach ($listPenjualan as $data) {
            $penjualan[$data->tanggal-1] = $data->jumlah;
        }

        for ($i = 0; $i < count($list_bulan); $i++) {
            if (!isset($penjualan[$i])) {
                $dataPenjualan[$i] = 0;
            } else {
                $dataPenjualan[$i] = $penjualan[$i];
            }
        }

        $coloursPenjualan = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . '33';
        $bordersPenjualan = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . 'FF';

        $chartPenjualan = new ChartModel();

        $chartPenjualan->labels = $list_bulan;
        $chartPenjualan->dataset = $dataPenjualan;
        $chartPenjualan->colours = $coloursPenjualan;
        $chartPenjualan->borders = $bordersPenjualan;
     
        $listPemasukanBulan = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                    ->select(DB::raw('MONTH(jurnal.tanggal) AS tanggal'), 
                                    DB::raw('IFNULL(SUM(jurnal.debit+jurnal.kredit), 0) AS jumlah'))
                                    ->where(DB::raw('YEAR(jurnal.tanggal)'), now()->year)
                                    ->where('akun.kode', 'like', '4%')
                                    ->groupBy(DB::raw('MONTH(jurnal.tanggal)'))
                                    ->get();
        
        $listPengeluaranBulan = JurnalModel::join('akun', 'akun.id', '=', 'jurnal.id_akun')
                                    ->select(DB::raw('MONTH(jurnal.tanggal) AS tanggal'), 
                                    DB::raw('IFNULL(SUM(jurnal.debit+jurnal.kredit), 0) AS jumlah'))
                                    ->where(DB::raw('YEAR(jurnal.tanggal)'), now()->year)
                                    ->where('akun.kode', 'like', '5%')
                                    ->orWhere('akun.kode', 'like', '6%')
                                    ->groupBy(DB::raw('MONTH(jurnal.tanggal)'))
                                    ->get();

        foreach ($listPemasukanBulan as $data) {
            $dataPemasukan[$data->tanggal-1] = $data->jumlah;
        }

        foreach ($listPengeluaranBulan as $data) {
            $dataPengeluaran[$data->tanggal-1] = $data->jumlah;
        }

        for ($i = 0; $i < count($list_bulan); $i++) {
            if (!isset($dataPemasukan[$i])) {
                $dataPemasukan[$i] = 0;
            }

            if (!isset($dataPengeluaran[$i])) {
                $dataPengeluaran[$i] = 0;
            }

            $dataLaba[$i] = $dataPemasukan[$i] - $dataPengeluaran[$i];
        }

        $coloursLaba = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . '33';
        $bordersLaba = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . 'FF';

        $chartLaba = new ChartModel();

        $chartLaba->labels = $list_bulan;
        $chartLaba->dataset = $dataLaba;
        $chartLaba->colours = $coloursLaba;
        $chartLaba->borders = $bordersLaba;

        return view ('toko.dashboard', compact('chartLaba', 'chartPenjualan', 'data_notif_hutang', 'data_notif', 'data_notified', 'total_penjualan', 'total_pembelian', 'total_pendapatan', 'year'));
    }
}
