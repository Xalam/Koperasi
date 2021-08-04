<?php

namespace App\Http\Controllers\Toko\Laporan\KasMasuk;

use App\Exports\Toko\Laporan\LaporanKasMasukExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\PemasukanModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use App\Models\Toko\Transaksi\Piutang\PiutangModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanKasMasukController extends Controller
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
        $jenis_pemasukan = $request->input('jenis_pemasukan');

        $data_pemasukan = PemasukanModel::all();

        $pemasukan[0] = "Semua";
        foreach ($data_pemasukan as $data) {
            $pemasukan[$data->id] = $data->nama;
        }
        
        if ($tanggal_awal && $tanggal_akhir) {
            if ($jenis_pemasukan == 2) {
                $laporan_kas_masuk = PenjualanModel::join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'penjualan.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                                            DB::raw('IFNULL(tb_anggota.kd_anggota, "Masyarakat Umum") AS kode_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.nama_anggota, "Masyarakat Umum") AS nama_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.jabatan, "-") AS status'), 
                                                            'jurnal.keterangan AS keterangan', 'detail_jual.total_harga AS jumlah_transaksi')
                                                    ->whereBetween('penjualan.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct()
                                                    ->get();
            } else if ($jenis_pemasukan == 1) {
                $laporan_kas_masuk = PiutangModel::join('terima_piutang', 'terima_piutang.id_piutang', '=', 'piutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'terima_piutang.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'piutang.id_anggota')
                                                    ->select('terima_piutang.nomor AS nomor', 'terima_piutang.tanggal AS tanggal', 
                                                            DB::raw('IFNULL(tb_anggota.kd_anggota, "Masyarakat Umum") AS kode_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.nama_anggota, "Masyarakat Umum") AS nama_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.jabatan, "-") AS status'), 
                                                            'jurnal.keterangan AS keterangan', 'terima_piutang.terima_piutang AS jumlah_transaksi')
                                                    ->whereBetween('terima_piutang.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->distinct()
                                                    ->get();
            } else {
                $laporan_kas_masuk_pembelian = PenjualanModel::join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'penjualan.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                                            DB::raw('IFNULL(tb_anggota.kd_anggota, "Masyarakat Umum") AS kode_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.nama_anggota, "Masyarakat Umum") AS nama_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.jabatan, "-") AS status'), 
                                                            'jurnal.keterangan AS keterangan', 'detail_jual.total_harga AS jumlah_transaksi')
                                                    ->whereBetween('penjualan.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct();

                                                    
                $laporan_kas_masuk = PiutangModel::join('terima_piutang', 'terima_piutang.id_piutang', '=', 'piutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'terima_piutang.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'piutang.id_anggota')
                                                    ->select('terima_piutang.nomor AS nomor', 'terima_piutang.tanggal AS tanggal', 
                                                            DB::raw('IFNULL(tb_anggota.kd_anggota, "Masyarakat Umum") AS kode_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.nama_anggota, "Masyarakat Umum") AS nama_anggota'), 
                                                            DB::raw('IFNULL(tb_anggota.jabatan, "-") AS status'), 
                                                            'jurnal.keterangan AS keterangan', 'terima_piutang.terima_piutang AS jumlah_transaksi')
                                                    ->whereBetween('terima_piutang.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->distinct()
                                                    ->union($laporan_kas_masuk_pembelian)
                                                    ->get();
            }
    
            return view ('toko.laporan.kas_masuk.index', compact('cur_date', 'laporan_kas_masuk', 'data_notified', 'data_notif', 'data_notif_hutang', 'pemasukan', 'tanggal_awal', 'tanggal_akhir', 'jenis_pemasukan'));
        } else {
            return view ('toko.laporan.kas_masuk.index', compact('cur_date', 'pemasukan', 'data_notified', 'data_notif', 'data_notif_hutang'));
        }
    }

    public function print($jenis_pemasukan, $tanggal_awal, $tanggal_akhir) {
        if ($tanggal_awal && $tanggal_akhir) {
            if ($jenis_pemasukan == 2) {
                $laporan_kas_masuk = PenjualanModel::join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'penjualan.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                                            'tb_anggota.nama_anggota AS nama_anggota', 'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.jabatan AS status', 
                                                            'detail_jual.total_harga AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('penjualan.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct()
                                                    ->get();
            } else if ($jenis_pemasukan == 1) {
                $laporan_kas_masuk = PiutangModel::join('terima_piutang', 'terima_piutang.id_piutang', '=', 'piutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'terima_piutang.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'piutang.id_anggota')
                                                    ->select('terima_piutang.nomor AS nomor', 'terima_piutang.tanggal AS tanggal', 
                                                            'tb_anggota.nama_anggota AS nama_anggota', 'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.jabatan AS status', 
                                                            'terima_piutang.terima_piutang AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('terima_piutang.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->distinct()
                                                    ->get();
            } else {
                $laporan_kas_masuk_pembelian = PenjualanModel::join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'penjualan.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('detail_jual.nomor AS nomor', 'penjualan.tanggal AS tanggal', 
                                                            'tb_anggota.nama_anggota AS nama_anggota', 'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.jabatan AS status', 
                                                            'detail_jual.total_harga AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('penjualan.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct();

                                                    
                $laporan_kas_masuk = PiutangModel::join('terima_piutang', 'terima_piutang.id_piutang', '=', 'piutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'terima_piutang.nomor_jurnal')
                                                    ->leftJoin('tb_anggota', 'tb_anggota.id', '=', 'piutang.id_anggota')
                                                    ->select('terima_piutang.nomor AS nomor', 'terima_piutang.tanggal AS tanggal', 
                                                            'tb_anggota.nama_anggota AS nama_anggota', 'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.jabatan AS status', 
                                                            'terima_piutang.terima_piutang AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('terima_piutang.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->distinct()
                                                    ->union($laporan_kas_masuk_pembelian)
                                                    ->get();
            }
        }

        return view ('toko.laporan.kas_masuk.print', compact('laporan_kas_masuk'));
        
        // $pdf = PDF::loadview('toko.laporan.penjualan.print', ['laporan_kas_masuk'=>$laporan_kas_masuk]);
        // return $pdf->download('Laporan Pembelian ' . $pemasukan . $tanggal . '.pdf');
    }

    public function export($jenis_pemasukan, $tanggal_awal, $tanggal_akhir) {
        if ($jenis_pemasukan == 0) {
            $pemasukan = 'Semua';
        } else {
            $pemasukan = PemasukanModel::select('*')
                                        ->where('id', $jenis_pemasukan)->first();

            $pemasukan = $pemasukan->nama;
        }

        return Excel::download(new LaporanKasMasukExport(
                                    $jenis_pemasukan,
                                    $tanggal_awal, 
                                    $tanggal_akhir
                                ), 'Laporan Kas Masuk ' . $pemasukan . ' ' . $tanggal_awal . ' Sampai ' . $tanggal_akhir . '.xlsx');
    }
}
