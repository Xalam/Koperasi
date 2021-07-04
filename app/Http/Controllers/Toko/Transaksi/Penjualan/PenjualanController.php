<?php

namespace App\Http\Controllers\Toko\Transaksi\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Akun\AkunModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Master\Anggota\AnggotaModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\PembayaranModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanBarangModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use App\Models\Toko\Transaksi\Piutang\PiutangModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index() {
        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            }
        }

        $cur_date = Carbon::now();

        $data_barang = BarangModel::orderBy('nama')
                                    ->get();

        $barang[''] = '-- Pilih Nama Barang --';
        foreach ($data_barang as $data) {
            $barang[$data->id] = $data->nama;
        }

        $data_barang = BarangModel::orderBy('kode')
                                    ->get();

        $kode_barang[''] = '-- Pilih Kode Barang --';
        foreach ($data_barang as $data) {
            $kode_barang[$data->id] = $data->kode;
        }
        
        $data_pembayaran = PembayaranModel::orderBy('nama')
                                            ->get();

        foreach ($data_pembayaran as $data) {
            $pembayaran[$data->id] = $data->nama;
        }

        $data_anggota = AnggotaModel::orderBy('nama_anggota')
                                        ->get();

        $anggota[''] = '-- Pilih Nama Anggota --';
        $anggota[0] = 'Masyarakat Umum';
        foreach ($data_anggota as $data) {
            $anggota[$data->id] = $data->nama_anggota;
        }

        $data_anggota = AnggotaModel::orderBy('kd_anggota')
                                        ->get();

        $kode_anggota[''] = '-- Pilih Kode Anggota --';
        $kode_anggota[0] = 'Masyarakat Umum';
        foreach ($data_anggota as $data) {
            $kode_anggota[$data->id] = $data->kd_anggota;
        }

        return view('toko.transaksi.penjualan.index', compact('barang', 'cur_date', 'data_notified', 'data_notif', 'kode_barang', 'kode_anggota', 'pembayaran', 'anggota'));
    }

    public function show($nomor) {
        $barang_penjualan = PenjualanBarangModel::where('detail_jual.nomor', $nomor)
                                    ->join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                    ->select('detail_jual.id AS id', 'detail_jual.nomor AS nomor_penjualan', 'detail_jual.jumlah AS jumlah_barang',
                                            'detail_jual.total_harga AS total_harga', 'detail_jual.id_barang AS id_barang',
                                            'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                            'barang.harga_jual AS harga_jual')
                                    ->get();

        $anggota_penjualan = PenjualanModel::where('penjualan.nomor', $nomor)
                                    ->join('tb_anggota', 'tb_anggota.id', '=', 'penjualan.id_anggota')
                                    ->select('penjualan.tanggal AS tanggal', 'penjualan.nomor AS nomor_penjualan', 'penjualan.id_anggota AS id_anggota',
                                            'penjualan.jumlah_bayar AS jumlah_bayar', 'penjualan.jumlah_kembalian AS jumlah_kembalian', 
                                            'penjualan.pembayaran AS pembayaran', 'tb_anggota.alamat AS alamat', 'tb_anggota.telepon AS telepon')
                                    ->first();

        return response()->json(['code'=>200, 'barang_penjualan' => $barang_penjualan, 'anggota_penjualan' => $anggota_penjualan]);
    }

    public function store(Request $request) {
        $barang = PenjualanBarangModel::where('nomor', $request->nomor)
                                        ->where('id_barang', $request->id_barang)->first();

        if ($barang) {
            PenjualanBarangModel::where('id_barang', $request->id_barang)->update([
                'jumlah' => $barang->jumlah + $request->jumlah, 
                'total_harga' => $barang->total_harga + $request->total_harga
                ]);
        } else {
            PenjualanBarangModel::create($request->all());
        }
        
        return response()->json(['code'=>200]);
    }

    public function sell(Request $request) {
        $nomor = $request->input('nomor');
        $tanggal = $request->input('tanggal');

        PenjualanBarangModel::where('nomor', $nomor)->update(['submited' => 1]);

        $data_barang = PenjualanBarangModel::where('nomor', $nomor)->get();

        foreach ($data_barang as $data) {
            $barang = BarangModel::where('id', $data->id_barang)->first();

            BarangModel::where('id', $data->id_barang)->update([
                'stok' => $barang->stok - $data->jumlah]);
        }

        if ($request->input('pembayaran') == 2) {
            $kas = AkunModel::where('kode', 1102)->first();
            $pendapatan = AkunModel::where('kode', 4102)->first();

            AkunModel::where('kode', 1102)->update([
                'debit' => $kas->debit + $request->input('jumlah_harga')
            ]);

            AkunModel::where('kode', 4102)->update([
                'kredit' => $pendapatan->kredit + $request->input('jumlah_harga')
            ]);

            $persediaan = AkunModel::where('kode', 1131)->first();
            $hpp = AkunModel::where('kode', 5101)->first();

            $jumlah_hpp = PenjualanBarangModel::join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                                ->select('detail_jual.*', DB::raw('SUM(barang.hpp*detail_jual.jumlah) AS total_hpp'))
                                                ->where('detail_jual.nomor', $nomor)->first(); 

            AkunModel::where('kode', 1131)->update([
                'debit' => $persediaan->debit - $jumlah_hpp->total_hpp
            ]);

            AkunModel::where('kode', 5101)->update([
                'debit' => $hpp->debit + $jumlah_hpp->total_hpp
            ]);
            
            $keterangan = "Penjualan barang secara tunai.";
                
            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $tanggal,
                'keterangan' => $keterangan,
                'id_akun' => $hpp->id,
                'debit' => $jumlah_hpp->total_hpp,
                'kredit' => 0
            ]); 
                
            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $request->input('tanggal'),
                'keterangan' => $keterangan,
                'id_akun' => $kas->id,
                'debit' => $request->input('jumlah_harga'),
                'kredit' => 0
            ]); 

            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $tanggal,
                'keterangan' => $keterangan,
                'id_akun' => $persediaan->id,
                'debit' => 0,
                'kredit' => $jumlah_hpp->total_hpp
            ]); 
                
            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $request->input('tanggal'),
                'keterangan' => $keterangan,
                'id_akun' => $pendapatan->id,
                'debit' => 0,
                'kredit' => $request->input('jumlah_harga')
            ]);

            $jumlah_bayar = $request->input('jumlah_bayar');
            $jumlah_kembalian = $request->input('jumlah_kembalian');
        } else {
            $piutang = AkunModel::where('kode', 1122)->first();
            $pendapatan = AkunModel::where('kode', 4102)->first();

            AkunModel::where('kode', 1122)->update([
                'debit' => $piutang->debit + $request->input('jumlah_harga')
            ]);

            AkunModel::where('kode', 4102)->update([
                'kredit' => $pendapatan->kredit + $request->input('jumlah_harga')
            ]);

            $persediaan = AkunModel::where('kode', 1131)->first();
            $hpp = AkunModel::where('kode', 5101)->first();

            $jumlah_hpp = PenjualanBarangModel::join('barang', 'barang.id', '=', 'detail_jual.id_barang')
                                                ->select('detail_jual.*', DB::raw('SUM(barang.hpp*detail_jual.jumlah) AS total_hpp'))
                                                ->where('detail_jual.nomor', $nomor)->first(); 

            AkunModel::where('kode', 1131)->update([
                'debit' => $persediaan->debit - $jumlah_hpp->total_hpp
            ]);

            AkunModel::where('kode', 5101)->update([
                'debit' => $hpp->debit + $jumlah_hpp->total_hpp
            ]);

            PiutangModel::create([
                'nomor_jual' => $request->input('nomor'),
                'id_anggota' => $request->input('kode_anggota'),
                'jumlah_piutang' => $request->input('jumlah_harga'),
                'sisa_piutang' => $request->input('jumlah_harga')
            ]);
            
            $keterangan = "Penjualan barang secara kredit.";

            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $request->input('tanggal'),
                'keterangan' => $keterangan,
                'id_akun' => $piutang->id,
                'debit' => $request->input('jumlah_harga'),
                'kredit' => 0
            ]); 
                
            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $request->input('tanggal'),
                'keterangan' => $keterangan,
                'id_akun' => $hpp->id,
                'debit' => $jumlah_hpp->total_hpp,
                'kredit' => 0
            ]);
                
            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $request->input('tanggal'),
                'keterangan' => $keterangan,
                'id_akun' => $pendapatan->id,
                'debit' => 0,
                'kredit' => $request->input('jumlah_harga')
            ]); 
                
            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $request->input('tanggal'),
                'keterangan' => $keterangan,
                'id_akun' => $persediaan->id,
                'debit' => 0,
                'kredit' => $jumlah_hpp->total_hpp
            ]); 

            $jumlah_bayar = 0;
            $jumlah_kembalian = 0;
        }
        
        PenjualanModel::create([
            'tanggal' => $request->input('tanggal'),
            'nomor' => $request->input('nomor'),
            'nomor_jurnal' => $request->input('nomor_jurnal'),
            'id_anggota' => $request->input('kode_anggota'),
            'jumlah_harga' => $request->input('jumlah_harga'),
            'jumlah_bayar' => $jumlah_bayar,
            'jumlah_kembalian' => $jumlah_kembalian,
            'pembayaran' => $request->input('pembayaran')
        ]);
        
        return redirect('toko/transaksi/penjualan');
    }

    public function delete($id) {
        PenjualanBarangModel::where('id', $id)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function cancel(Request $request) {
        $nomor = $request->input('nomor');

        PenjualanModel::where('nomor', $nomor)->delete();
        PenjualanBarangModel::where('nomor', $nomor)->delete();
        
        return response()->json(['code'=>200]);
    }
}
