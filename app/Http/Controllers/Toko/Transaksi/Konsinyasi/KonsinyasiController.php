<?php

namespace App\Http\Controllers\Toko\Transaksi\Konsinyasi;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Akun\AkunModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\Konsinyasi\KonsinyasiDetailModel;
use App\Models\Toko\Transaksi\Konsinyasi\KonsinyasiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KonsinyasiController extends Controller
{
    public function index() {
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

        $data_konsinyasi = KonsinyasiModel::all();

        if (count($data_konsinyasi) > 0) {
            $pembayaran[''] = "-- Pilih Nomor Beli --";
        } else {
            $pembayaran[''] = "-- Tidak Ada Data Konsinyasi --";
        }
            
        foreach ($data_konsinyasi as $data) {
            $pembayaran[$data->id] = $data->nomor_beli;
        }

        $data_supplier = SupplierModel::orderBy('nama')
                                        ->get();

        $supplier[''] = '-- Pilih Nama Supplier --';
        foreach ($data_supplier as $data) {
            $supplier[$data->id] = $data->nama;
        }

        $data_supplier = SupplierModel::orderBy('kode')
                                        ->get();
                                        
        $kode_supplier[''] = '-- Pilih Kode Supplier --';
        foreach ($data_supplier as $data) {
            $kode_supplier[$data->id] = $data->kode;
        }

        $konsinyasi = KonsinyasiModel::join('supplier', 'supplier.id', '=', 'konsinyasi.id_supplier')
                            ->join('pembelian', 'pembelian.nomor', '=', 'konsinyasi.nomor_beli')
                            ->select('konsinyasi.*', 'pembelian.tanggal AS tanggal_beli', 
                            'supplier.kode AS kode_supplier', 'supplier.nama AS nama_supplier')
                            ->get();

        return view('toko.transaksi.konsinyasi.index', compact('cur_date', 'data_notified', 'data_notif', 'konsinyasi', 'kode_supplier', 'pembayaran', 'supplier'));
    }
    
    public function show($nomor_beli) {
        $detail_konsinyasi = KonsinyasiDetailModel::join('konsinyasi', 'konsinyasi.id', '=', 'detail_konsinyasi.id_konsinyasi')
                                    ->select('detail_konsinyasi.*', 'konsinyasi.nomor_beli AS nomor_konsinyasi')
                                    ->where('detail_konsinyasi.id_konsinyasi', $nomor_beli)
                                    ->get();

        $supplier_konsinyasi = KonsinyasiModel::join('supplier', 'supplier.id', '=', 'konsinyasi.id_supplier')
                                    ->select('supplier.nama AS nama_supplier', 'supplier.id AS id_supplier', 
                                    'supplier.kode AS kode_supplier', 'konsinyasi.sisa_konsinyasi AS sisa_konsinyasi',
                                    'konsinyasi.jumlah_konsinyasi AS jumlah_konsinyasi')
                                    ->where('konsinyasi.id', $nomor_beli)
                                    ->first();

        return response()->json(['code'=>200, 'detail_konsinyasi' => $detail_konsinyasi, 'supplier_konsinyasi' => $supplier_konsinyasi]);
    }

    public function store(Request $request) {
        $id_konsinyasi = $request->input('id_konsinyasi');
        $angsuran = $request->input('angsuran');
        $sisa_konsinyasi = $request->input('sisa_konsinyasi');

        $jumlah_angsuran = KonsinyasiModel::where('id', $id_konsinyasi)->first()->jumlah_angsuran;
 
        KonsinyasiModel::where('id', $id_konsinyasi)->update([
            'jumlah_angsuran' => $jumlah_angsuran + $angsuran,
            'sisa_konsinyasi' => $sisa_konsinyasi
        ]);
        
        $data_konsinyasi = KonsinyasiModel::where('id', $id_konsinyasi)->first();

        if ($data_konsinyasi->sisa_konsinyasi == 0) {
            KonsinyasiModel::where('id', $id_konsinyasi)->update([
                'status' => 1
            ]);
        }

        $kas = AkunModel::where('kode', 1102)->first();

        AkunModel::where('kode', 1102)->update([
            'debit' => $kas->debit - $angsuran
        ]);

        $konsinyasi = AkunModel::where('kode', 2101)->first();

        AkunModel::where('kode', 2101)->update([
            'kredit' => $konsinyasi->kredit - $angsuran
        ]);

        KonsinyasiDetailModel::create($request->all());
            
        $keterangan = "Penerimaan angsuran.";

        JurnalModel::create([
            'nomor' => $request->input('nomor_jurnal'),
            'tanggal' => $request->input('tanggal'),
            'keterangan' => $keterangan,
            'id_akun' => $konsinyasi->id,
            'debit' => $angsuran,
            'kredit' => 0 
        ]); 

        JurnalModel::create([
            'nomor' => $request->input('nomor_jurnal'),
            'tanggal' => $request->input('tanggal'),
            'keterangan' => $keterangan,
            'id_akun' => $kas->id,
            'debit' => 0,
            'kredit' => $angsuran
        ]); 
        
        return response()->json(['code'=>200]);
    }

    public function delete($id) {
        $data_angsuran = KonsinyasiDetailModel::where('id', $id)->first();
        $data_konsinyasi = KonsinyasiModel::where('id', $data_angsuran->id_konsinyasi)->first();

        KonsinyasiModel::where('id', $data_angsuran->id_konsinyasi)->update([
            'jumlah_angsuran' => $data_konsinyasi->jumlah_angsuran - $data_angsuran->angsuran
        ]);
        
        $data_konsinyasi = KonsinyasiModel::where('id', $data_angsuran->id_konsinyasi)->first();

        KonsinyasiModel::where('id', $data_angsuran->id_konsinyasi)->update([
            'sisa_konsinyasi' => $data_konsinyasi->jumlah_konsinyasi - $data_konsinyasi->jumlah_angsuran
        ]);
        
        $data_konsinyasi = KonsinyasiModel::where('id', $data_angsuran->id_konsinyasi)->first();

        if ($data_konsinyasi->sisa_konsinyasi > 0) {
            KonsinyasiModel::where('id', $data_angsuran->id_konsinyasi)->update([
                'status' => 0
            ]);
        }

        KonsinyasiDetailModel::where('id', $id)->delete();
        JurnalModel::where('nomor', $data_angsuran->nomor_jurnal)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function cancel(Request $request) {
        return response()->json(['code'=>200]);
    }
}
