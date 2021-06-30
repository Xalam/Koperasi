<?php

namespace App\Http\Controllers\Toko\Laporan\Penjualan;

use App\Exports\Toko\Laporan\LaporanPenjualanExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\PembayaranModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPenjualanController extends Controller
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

        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $type_pembayaran = $request->input('type_pembayaran');

        $data_pembayaran = PembayaranModel::all();

        $pembayaran[0] = "Semua";
        foreach ($data_pembayaran as $data) {
            $pembayaran[$data->id] = $data->nama;
        }

        if ($tanggal_awal && $tanggal_akhir) {
            if ($type_pembayaran > 0) {
                $laporan_penjualan = PenjualanModel::whereBetween('penjualan.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', $type_pembayaran)
                                                    ->join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                                    ->leftJoin('anggota', 'anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_jual.jumlah AS jumlah', 
                                                            'detail_jual.total_harga AS total_harga', 'detail_jual.nomor AS nomor',
                                                            'penjualan.tanggal AS tanggal', 'anggota.nama AS nama_anggota', 
                                                            'anggota.kode AS kode_anggota', 'anggota.jabatan AS status')
                                                    ->get();
            } else {
                $laporan_penjualan = PenjualanModel::whereBetween('penjualan.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                                    ->leftJoin('anggota', 'anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_jual.jumlah AS jumlah', 
                                                            'detail_jual.total_harga AS total_harga', 'detail_jual.nomor AS nomor',
                                                            'penjualan.tanggal AS tanggal', 'anggota.nama AS nama_anggota', 
                                                            'anggota.kode AS kode_anggota', 'anggota.jabatan AS status')
                                                    ->get();
            }
    
            return view ('toko.laporan.penjualan.index', compact('cur_date', 'laporan_penjualan', 'data_notified', 'data_notif', 'pembayaran', 'tanggal_awal', 'tanggal_akhir', 'type_pembayaran'));
        } else {
            return view ('toko.laporan.penjualan.index', compact('cur_date', 'pembayaran', 'data_notified', 'data_notif'));
        }
    }

    public function print($type_pembayaran, $tanggal_awal, $tanggal_akhir) {
        
        if ($type_pembayaran == 0) {
            $pembayaran = 'Semua';
        } else {
            $pembayaran = PembayaranModel::select('*')
                                        ->where('id', $type_pembayaran)->first();

            $pembayaran = $pembayaran->nama;
        }

        if ($tanggal_awal != $tanggal_akhir) {
            $tanggal = ' ' . $tanggal_awal . ' Sampai ' . $tanggal_akhir;
        } else {
            $tanggal = ' ' . $tanggal_awal;
        }

        $laporan_penjualan = '';

        if ($tanggal_awal && $tanggal_akhir) {
            if ($type_pembayaran > 0) {
                $laporan_penjualan = PenjualanModel::whereBetween('penjualan.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', $type_pembayaran)
                                                    ->join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                                    ->leftJoin('anggota', 'anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_jual.jumlah AS jumlah', 
                                                            'detail_jual.total_harga AS total_harga', 'detail_jual.nomor AS nomor',
                                                            'penjualan.tanggal AS tanggal', 'anggota.nama AS nama_anggota', 
                                                            'anggota.kode AS kode_anggota', 'anggota.jabatan AS status')
                                                    ->get();
            } else {
                $laporan_penjualan = PenjualanModel::whereBetween('penjualan.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->join('detail_jual', 'detail_jual.nomor', '=', 'penjualan.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                                    ->leftJoin('anggota', 'anggota.id', '=', 'penjualan.id_anggota')
                                                    ->select('barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_jual.jumlah AS jumlah', 
                                                            'detail_jual.total_harga AS total_harga', 'detail_jual.nomor AS nomor',
                                                            'penjualan.tanggal AS tanggal', 'anggota.nama AS nama_anggota', 
                                                            'anggota.kode AS kode_anggota', 'anggota.jabatan AS status')
                                                    ->get();
            }
        }
    
        return view ('toko.laporan.penjualan.print', compact('laporan_penjualan'));
        
        // $pdf = PDF::loadview('toko.laporan.penjualan.print', ['laporan_penjualan'=>$laporan_penjualan]);
        // return $pdf->download('Laporan Penjualan ' . $pembayaran . $tanggal . '.pdf');
    }

    public function export($type_pembayaran, $tanggal_awal, $tanggal_akhir) {
        
        if ($type_pembayaran == 0) {
            $pembayaran = 'Semua';
        } else {
            $pembayaran = PembayaranModel::select('*')
                                        ->where('id', $type_pembayaran)->first();

            $pembayaran = $pembayaran->nama;
        }

        if ($tanggal_awal != $tanggal_akhir) {
            $tanggal = ' ' . $tanggal_awal . ' Sampai ' . $tanggal_akhir;
        } else {
            $tanggal = ' ' . $tanggal_awal;
        }

        return Excel::download(new LaporanPenjualanExport(
                                    $tanggal_awal, 
                                    $tanggal_akhir, 
                                    $type_pembayaran
                                ), 'Laporan Penjualan ' . $pembayaran . $tanggal . '.xlsx');
    }
}