<?php

namespace App\Http\Controllers\Toko\Transaksi\Hutang;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Toko\Master\Akun\AkunModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use App\Models\Toko\Transaksi\Hutang\HutangDetailModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HutangController extends Controller
{
    public function index() {
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

        HutangModel::where(DB::raw('DATE_ADD(DATE(NOW()), INTERVAL 3 DAY)'), '>=', DB::raw('DATE(jatuh_tempo)'))->update([
            'alert_status' => 1
        ]);

        $data_notif_hutang = HutangModel::join('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                    ->select('hutang.*', 'supplier.nama AS nama_supplier')
                                    ->get();

        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_hutang = HutangModel::all();

        if (count($data_hutang) > 0) {
            $pembayaran[''] = "-- Pilih Nomor Beli --";
        } else {
            $pembayaran[''] = "-- Tidak Ada Data Hutang --";
        }
            
        foreach ($data_hutang as $data) {
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

        $hutang = HutangModel::join('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                            ->join('pembelian', 'pembelian.nomor', '=', 'hutang.nomor_beli')
                            ->select('hutang.*', 'pembelian.tanggal AS tanggal_beli', 
                            'supplier.kode AS kode_supplier', 'supplier.nama AS nama_supplier')
                            ->get();

        return view('toko.transaksi.hutang.index', compact('cur_date', 'data_notified', 'data_notif', 'data_notif_hutang', 'data_hutang', 'hutang', 'kode_supplier', 'pembayaran', 'supplier'));
    }
    
    public function show($nomor_beli) {
        $detail_hutang = HutangDetailModel::join('hutang', 'hutang.id', '=', 'detail_hutang.id_hutang')
                                    ->select('detail_hutang.*', 'hutang.nomor_beli AS nomor_hutang')
                                    ->where('detail_hutang.id_hutang', $nomor_beli)
                                    ->get();

        $supplier_hutang = HutangModel::join('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                    ->select('supplier.nama AS nama_supplier', 'supplier.id AS id_supplier', 
                                    'supplier.kode AS kode_supplier', 'hutang.sisa_hutang AS sisa_hutang',
                                    'hutang.jumlah_hutang AS jumlah_hutang')
                                    ->where('hutang.id', $nomor_beli)
                                    ->first();

        return response()->json(['code'=>200, 'detail_hutang' => $detail_hutang, 'supplier_hutang' => $supplier_hutang]);
    }

    public function store(Request $request) {
        $id_hutang = $request->input('id_hutang');
        $angsuran = $request->input('angsuran');
        $sisa_hutang = $request->input('sisa_hutang');

        $jumlah_angsuran = HutangModel::where('id', $id_hutang)->first()->jumlah_angsuran;
 
        HutangModel::where('id', $id_hutang)->update([
            'jumlah_angsuran' => $jumlah_angsuran + $angsuran,
            'sisa_hutang' => $sisa_hutang
        ]);
        
        $data_hutang = HutangModel::where('id', $id_hutang)->first();

        if ($data_hutang->sisa_hutang == 0) {
            HutangModel::where('id', $id_hutang)->update([
                'status' => 1
            ]);
        }

        $kas = AkunModel::where('kode', 1102)->first();

        AkunModel::where('kode', 1102)->update([
            'debit' => $kas->debit - $angsuran
        ]);

        $hutang = AkunModel::where('kode', 2101)->first();

        AkunModel::where('kode', 2101)->update([
            'kredit' => $hutang->kredit - $angsuran
        ]);

        HutangDetailModel::create($request->all());
            
        $keterangan = "Pembayaran Utang.";

        JurnalModel::create([
            'nomor' => $request->input('nomor_jurnal'),
            'tanggal' => $request->input('tanggal'),
            'keterangan' => $keterangan,
            'id_akun' => $hutang->id,
            'debit' => $angsuran,
            'kredit' => 0 
        ]); 

        // JurnalUmum::create([
        //     'kode_jurnal' => $request->input('nomor_jurnal'),
        //     'id_akun' => $hutang->id,
        //     'tanggal' => $request->input('tanggal'),
        //     'keterangan' => $keterangan,
        //     'debet' => $angsuran,
        //     'kredit' => 0 
        // ]); 

        JurnalModel::create([
            'nomor' => $request->input('nomor_jurnal'),
            'tanggal' => $request->input('tanggal'),
            'keterangan' => $keterangan,
            'id_akun' => $kas->id,
            'debit' => 0,
            'kredit' => $angsuran
        ]); 

        // JurnalUmum::create([
        //     'kode_jurnal' => $request->input('nomor_jurnal'),
        //     'id_akun' => $kas->id,
        //     'tanggal' => $request->input('tanggal'),
        //     'keterangan' => $keterangan,
        //     'debet' => 0,
        //     'kredit' => $angsuran
        // ]); 
        
        return response()->json(['code'=>200, 'message' => 'Pembayaran Hutang Berhasil']);
    }

    public function delete($id) {
        $data_angsuran = HutangDetailModel::where('id', $id)->first();
        $data_hutang = HutangModel::where('id', $data_angsuran->id_hutang)->first();

        HutangModel::where('id', $data_angsuran->id_hutang)->update([
            'jumlah_angsuran' => $data_hutang->jumlah_angsuran - $data_angsuran->angsuran,
            'sisa_hutang' => $data_hutang->jumlah_hutang - $data_angsuran->angsuran
        ]);

        if ($data_hutang->sisa_hutang > 0) {
            HutangModel::where('id', $data_angsuran->id_hutang)->update([
                'status' => 0
            ]);
        }

        $kas = AkunModel::where('kode', 1102)->first();

        AkunModel::where('kode', 1102)->update([
            'debit' => $kas->debit + $data_angsuran->angsuran
        ]);

        $hutang = AkunModel::where('kode', 2101)->first();

        AkunModel::where('kode', 2101)->update([
            'kredit' => $hutang->kredit + $data_angsuran->angsuran
        ]);

        HutangDetailModel::where('id', $id)->delete();
        JurnalModel::where('nomor', $data_angsuran->nomor_jurnal)->delete();
        // JurnalUmum::where('kode_jurnal', $data_angsuran->nomor_jurnal)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function cancel(Request $request) {
        return response()->json(['code'=>200]);
    }

    public function removeNotification($id) {
        HutangModel::where('id', $id)->update([
            'alert_status' => 0
        ]);
    }
}
