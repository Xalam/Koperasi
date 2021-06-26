<?php

namespace App\Http\Controllers\Toko\Transaksi\Hutang;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Akun\AkunModel;
use App\Models\Toko\Master\Jurnal\JurnalModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use App\Models\Toko\Transaksi\Hutang\HutangDetailModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    public function index() {
        $data_hutang = HutangModel::all();

        if (count($data_hutang) > 0) {
            $pembayaran[0] = "-- Pilih Nomor Beli --";
        } else {
            $pembayaran[0] = "-- Tidak Ada Data Hutang --";
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

        return view('toko.transaksi.hutang.index', compact('hutang', 'kode_supplier', 'pembayaran', 'supplier'));
    }
    
    public function show($nomor_beli) {
        $detail_hutang = HutangDetailModel::join('hutang', 'hutang.id', '=', 'detail_hutang.id_hutang')
                                    ->select('detail_hutang.*', 'hutang.nomor_beli AS nomor_hutang')
                                    ->where('detail_hutang.id_hutang', $nomor_beli)
                                    ->get();

        $supplier_hutang = HutangModel::join('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                    ->select('supplier.nama AS nama_supplier', 'supplier.id AS id_supplier', 
                                    'supplier.kode AS kode_supplier', 'hutang.sisa_hutang AS sisa_hutang')
                                    ->where('hutang.id', $nomor_beli)
                                    ->first();

        return response()->json(['code'=>200, 'detail_hutang' => $detail_hutang, 'supplier_hutang' => $supplier_hutang]);
    }

    public function store(Request $request) {
        $id_hutang = $request->input('id_hutang');
        $angsuran = $request->input('angsuran');
        $sisa_hutang = $request->input('sisa_hutang');

        $jumlah_angsuran = HutangModel::where('id', $id_hutang)->sum('jumlah_angsuran');

        HutangModel::where('id', $id_hutang)->update([
            'jumlah_angsuran' => $jumlah_angsuran + $angsuran,
            'sisa_hutang' => $sisa_hutang
        ]);

        $kas = AkunModel::where('kode', 1102)->first()->debit;

        AkunModel::where('kode', 1102)->update([
            'debit' => $kas - $angsuran
        ]);

        $hutang = AkunModel::where('kode', 2101)->first()->kredit;

        AkunModel::where('kode', 2101)->update([
            'kredit' => $hutang - $angsuran
        ]);

        // JurnalModel::create([
        //     'nomor' => $request->input('nomor_beli'),
        //     'tanggal' => $request->input('nomor_beli'),
        //     'keterangan' => $request->input('nomor_beli'),
        //     'id_akun' => $request->input('nomor_beli'),
        //     'nomor' => $request->input('nomor_beli'),
        //     'nomor' => $request->input('nomor_beli'),
        // ]);

        HutangDetailModel::create($request->all());
        
        return response()->json(['code'=>200]);
    }

    public function cancel(Request $request) {
        return response()->json(['code'=>200]);
    }
}
