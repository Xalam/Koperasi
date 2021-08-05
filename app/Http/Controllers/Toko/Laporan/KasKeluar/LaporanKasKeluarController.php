<?php

namespace App\Http\Controllers\Toko\Laporan\KasKeluar;

use App\Exports\Toko\Laporan\LaporanKasKeluarExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use App\Models\Toko\Transaksi\PengeluaranModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanKasKeluarController extends Controller
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
        $jenis_pengeluaran = $request->input('jenis_pengeluaran');

        $data_pengeluaran = PengeluaranModel::all();

        $pengeluaran[0] = "Semua";
        foreach ($data_pengeluaran as $data) {
            $pengeluaran[$data->id] = $data->nama;
        }
        
        if ($tanggal_awal && $tanggal_akhir) {
            if ($jenis_pengeluaran == 2) {
                $laporan_kas_keluar = PembelianModel::join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'pembelian.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS nama_supplier', 'supplier.kode AS kode_supplier',
                                                            'detail_beli.total_harga AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('pembelian.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct()
                                                    ->get();
            } else if ($jenis_pengeluaran == 1) {
                $laporan_kas_keluar = HutangModel::join('detail_hutang', 'detail_hutang.id_hutang', '=', 'hutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'detail_hutang.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                                    ->select('detail_hutang.nomor AS nomor', 'detail_hutang.tanggal AS tanggal', 
                                                            'supplier.nama AS nama_supplier', 'supplier.kode AS kode_supplier',
                                                            'detail_hutang.angsuran AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('detail_hutang.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->distinct()
                                                    ->get();
            } else {
                $laporan_kas_keluar_pembelian = PembelianModel::join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'pembelian.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS nama_supplier', 'supplier.kode AS kode_supplier',
                                                            'detail_beli.total_harga AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('pembelian.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct();

                                                    
                $laporan_kas_keluar = HutangModel::join('detail_hutang', 'detail_hutang.id_hutang', '=', 'hutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'detail_hutang.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                                    ->select('detail_hutang.nomor AS nomor', 'detail_hutang.tanggal AS tanggal', 
                                                            'supplier.nama AS nama_supplier', 'supplier.kode AS kode_supplier',
                                                            'detail_hutang.angsuran AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('detail_hutang.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->distinct()
                                                    ->union($laporan_kas_keluar_pembelian)
                                                    ->get();
            }
    
            return view ('toko.laporan.kas_keluar.index', compact('cur_date', 'laporan_kas_keluar', 'data_notified', 'data_notif', 'data_notif_hutang', 'pengeluaran', 'tanggal_awal', 'tanggal_akhir', 'jenis_pengeluaran'));
        } else {
            return view ('toko.laporan.kas_keluar.index', compact('cur_date', 'pengeluaran', 'data_notified', 'data_notif', 'data_notif_hutang'));
        }
    }

    public function print($jenis_pengeluaran, $tanggal_awal, $tanggal_akhir) {
        
        if ($jenis_pengeluaran == 0) {
            $pengeluaran = 'Semua';
        } else {
            $pengeluaran = PengeluaranModel::select('*')
                                        ->where('id', $jenis_pengeluaran)->first();

            $pengeluaran = $pengeluaran->nama;
        }

        if ($tanggal_awal != $tanggal_akhir) {
            $tanggal = ' ' . $tanggal_awal . ' Sampai ' . $tanggal_akhir;
        } else {
            $tanggal = ' ' . $tanggal_awal;
        }

        $laporan_kas_keluar = '';

        if ($tanggal_awal && $tanggal_akhir) {
            if ($jenis_pengeluaran == 2) {
                $laporan_kas_keluar = PembelianModel::join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'pembelian.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS nama_supplier', 'supplier.kode AS kode_supplier',
                                                            'detail_beli.total_harga AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('pembelian.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct()
                                                    ->get();
            } else if ($jenis_pengeluaran == 1) {
                $laporan_kas_keluar = HutangModel::join('detail_hutang', 'detail_hutang.id_hutang', '=', 'hutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'detail_hutang.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                                    ->select('detail_hutang.nomor AS nomor', 'detail_hutang.tanggal AS tanggal', 
                                                            'supplier.nama AS nama_supplier', 'supplier.kode AS kode_supplier',
                                                            'detail_hutang.angsuran AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('detail_hutang.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->distinct()
                                                    ->get();
            } else {
                $laporan_kas_keluar_pembelian = PembelianModel::join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'pembelian.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS nama_supplier', 'supplier.kode AS kode_supplier',
                                                            'detail_beli.total_harga AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('pembelian.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', 2)
                                                    ->distinct();

                                                    
                $laporan_kas_keluar = HutangModel::join('detail_hutang', 'detail_hutang.id_hutang', '=', 'hutang.id')
                                                    ->join('jurnal', 'jurnal.nomor', '=', 'detail_hutang.nomor_jurnal')
                                                    ->leftJoin('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                                    ->select('detail_hutang.nomor AS nomor', 'detail_hutang.tanggal AS tanggal', 
                                                            'supplier.nama AS nama_supplier', 'supplier.kode AS kode_supplier',
                                                            'detail_hutang.angsuran AS jumlah_transaksi', 'jurnal.keterangan AS keterangan')
                                                    ->whereBetween('detail_hutang.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->distinct()
                                                    ->union($laporan_kas_keluar_pembelian)
                                                    ->get();
            }
        }

        return view ('toko.laporan.kas_keluar.print', compact('laporan_kas_keluar'));
        
        // $pdf = PDF::loadview('toko.laporan.pembelian.print', ['laporan_kas_keluar'=>$laporan_kas_keluar]);
        // return $pdf->download('Laporan Pembelian ' . $pengeluaran . $tanggal . '.pdf');
    }

    public function export($jenis_pengeluaran, $tanggal_awal, $tanggal_akhir) {
        
        if ($jenis_pengeluaran == 0) {
            $pengeluaran = 'Semua';
        } else {
            $pengeluaran = PengeluaranModel::select('*')
                                        ->where('id', $jenis_pengeluaran)->first();

            $pengeluaran = $pengeluaran->nama;
        }

        return Excel::download(new LaporanKasKeluarExport(
                                    $jenis_pengeluaran,
                                    $tanggal_awal, 
                                    $tanggal_akhir
                                ), 'Laporan Kas Keluar ' . $pengeluaran . ' ' . $tanggal_awal . ' Sampai ' . $tanggal_akhir . '.xlsx');
    }
}
