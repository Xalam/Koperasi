<?php

namespace App\Http\Controllers\Toko\Laporan\Pembelian;

use App\Exports\Toko\Laporan\LaporanPembelianExport;
use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\PembayaranModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPembelianController extends Controller
{
    public function index(Request $request) {
        $cur_date = Carbon::now();

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
                $laporan_pembelian = PembelianModel::whereBetween('pembelian.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', $type_pembayaran)
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS supplier', 'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_beli.harga_satuan AS harga_satuan', 
                                                            'detail_beli.jumlah AS jumlah', 'detail_beli.total_harga AS total_harga')
                                                    ->get();
            } else {
                $laporan_pembelian = PembelianModel::whereBetween('pembelian.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS supplier', 'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_beli.harga_satuan AS harga_satuan', 
                                                            'detail_beli.jumlah AS jumlah', 'detail_beli.total_harga AS total_harga')
                                                    ->get();
            }
    
            return view ('toko.laporan.pembelian.index', compact('cur_date', 'laporan_pembelian', 'data_notified', 'data_notif', 'pembayaran', 'tanggal_awal', 'tanggal_akhir', 'type_pembayaran'));
        } else {
            return view ('toko.laporan.pembelian.index', compact('cur_date', 'pembayaran', 'data_notified', 'data_notif'));
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

        $laporan_pembelian = '';

        if ($tanggal_awal && $tanggal_akhir) {
            if ($type_pembayaran > 0) {
                $laporan_pembelian = PembelianModel::whereBetween('pembelian.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->where('pembayaran', '=', $type_pembayaran)
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS supplier', 'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_beli.harga_satuan AS harga_satuan', 
                                                            'detail_beli.jumlah AS jumlah', 'detail_beli.total_harga AS total_harga')
                                                    ->get();
            } else {
                $laporan_pembelian = PembelianModel::whereBetween('pembelian.tanggal', [$tanggal_awal, $tanggal_akhir])
                                                    ->join('detail_beli', 'detail_beli.nomor', '=', 'pembelian.nomor')
                                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                                    ->select('detail_beli.nomor AS nomor', 'pembelian.tanggal AS tanggal', 
                                                            'supplier.nama AS supplier', 'barang.kode AS kode', 'barang.nama AS nama',
                                                            'barang.harga_jual AS harga_jual', 'detail_beli.harga_satuan AS harga_satuan', 
                                                            'detail_beli.jumlah AS jumlah', 'detail_beli.total_harga AS total_harga')
                                                    ->get();
            }
        }

        return view ('toko.laporan.pembelian.print', compact('laporan_pembelian'));
        
        // $pdf = PDF::loadview('toko.laporan.pembelian.print', ['laporan_pembelian'=>$laporan_pembelian]);
        // return $pdf->download('Laporan Pembelian ' . $pembayaran . $tanggal . '.pdf');
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

        return Excel::download(new LaporanPembelianExport(
                                    $tanggal_awal, 
                                    $tanggal_akhir, 
                                    $type_pembayaran
                                ), 'Laporan Pembelian ' . $pembayaran . $tanggal . '.xlsx');
    }
}