<?php

namespace App\Http\Controllers\Toko\Transaksi\TitipJual;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Akun\AkunModel;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\PembayaranModel;
use App\Models\Toko\Transaksi\TitipJual\TitipJualDetailModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\TitipJual\TitipJualModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class TitipJualController extends Controller
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

        return view('toko.transaksi.titip_jual.index', compact('barang', 'cur_date', 'data_notified', 'data_notif', 'kode_barang', 'kode_supplier', 'pembayaran', 'supplier'));
    }

    public function show($nomor) {
        $barang_titip_jual = TitipJualDetailModel::where('detail_titip_jual.nomor', $nomor)
                                    ->join('barang', 'barang.id', '=', 'detail_titip_jual.id_barang')
                                    ->select('detail_titip_jual.id AS id', 'detail_titip_jual.nomor AS nomor_titip_jual', 'detail_titip_jual.jumlah AS jumlah_barang',
                                            'detail_titip_jual.total_harga AS total_harga', 'detail_titip_jual.id_barang AS id_barang',
                                            'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                            'detail_titip_jual.harga_satuan AS harga_satuan')
                                    ->get();

        $supplier_titip_jual = TitipJualModel::where('titip_jual.nomor', $nomor)
                                    ->join('supplier', 'supplier.id', '=', 'titip_jual.id_supplier')
                                    ->select('titip_jual.tanggal AS tanggal', 'titip_jual.nomor AS nomor_titip_jual', 'titip_jual.id_supplier AS id_supplier',
                                            'titip_jual.jumlah_bayar AS jumlah_bayar', 'titip_jual.jumlah_kembalian AS jumlah_kembalian', 
                                            'supplier.alamat AS alamat', 'supplier.telepon AS telepon')
                                    ->first();

        return response()->json(['code'=>200, 'barang_titip_jual' => $barang_titip_jual, 'supplier_titip_jual' => $supplier_titip_jual]);
    }

    public function store(Request $request) {
        $barang = TitipJualDetailModel::where('nomor', $request->nomor)
                                        ->where('id_barang', $request->id_barang)->first();

        if ($barang) {
            TitipJualDetailModel::where('nomor', $request->nomor)
                                ->where('id_barang', $request->id_barang)->update([
                'harga_satuan' => ($barang->total_harga + ($request->jumlah * $request->harga_satuan)) / ($barang->jumlah + $request->jumlah), 
                'jumlah' => $barang->jumlah + $request->jumlah, 
                'total_harga' => $barang->total_harga + $request->total_harga
                ]);
        } else {
            TitipJualDetailModel::create($request->all());
        }
        
        return response()->json(['code'=>200]);
    }

    public function buy(Request $request) {
        $barang = [];
        $cur_date = "";
        $kode_supplier = [];
        $kode_barang = [];
        $pembayaran = [];
        $supplier = [];
        $data_notified = [];
        $data_notif = [];

        $nomor = $request->input('nomor');

        $data_barang = TitipJualDetailModel::where('nomor', $nomor)->get();

        if (count($data_barang) > 0) {
            TitipJualDetailModel::where('nomor', $nomor)->update(['submited' => 1]);

            foreach ($data_barang as $data) {
                $barang = BarangModel::where('id', $data->id_barang)->first();

                BarangModel::where('id', $data->id_barang)->update([
                    'hpp' => ($barang->stok * $barang->hpp + $data->total_harga) / ($barang->stok + $data->jumlah),
                    'stok' => $barang->stok + $data->jumlah]);
            }

            $persediaan_konsinyasi = AkunModel::where('kode', 1131)->first();
            $hutang_konsinyasi = AkunModel::where('kode', 2102)->first();

            AkunModel::where('kode', 1131)->update([
                'debit' => $persediaan_konsinyasi->debit + $request->input('jumlah_harga')
            ]);

            AkunModel::where('kode', 2102)->update([
                'debit' => $hutang_konsinyasi->debit + $request->input('jumlah_harga')
            ]);

            $jumlah_bayar = 0;
            $jumlah_kembalian = 0;

            $tanggal = Carbon::parse($request->input('tanggal'))->format('y-m-d');

            HutangModel::create([
                'nomor_beli' => $request->input('nomor'),
                'id_supplier' => $request->input('kode_supplier'),
                'jumlah_hutang' => $request->input('jumlah_harga'),
                'jatuh_tempo' => Carbon::parse($tanggal)->addDays($request->input('tempo')),
                'sisa_hutang' => $request->input('jumlah_harga')
            ]);

            $keterangan = "Titip Jual barang secara kredit.";

            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $request->input('tanggal'),
                'keterangan' => $keterangan,
                'id_akun' => $persediaan_konsinyasi->id,
                'debit' => $request->input('jumlah_harga'),
                'kredit' => 0
            ]); 
                
            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $request->input('tanggal'),
                'keterangan' => $keterangan,
                'id_akun' => $hutang_konsinyasi->id,
                'debit' => 0,
                'kredit' => $request->input('jumlah_harga')
            ]);
            
            TitipJualModel::create([
                'tanggal' => $request->input('tanggal'),
                'nomor' => $request->input('nomor'),
                'id_supplier' => $request->input('kode_supplier'),
                'jumlah_harga' => $request->input('jumlah_harga'),
                'jumlah_bayar' => $jumlah_bayar,
                'jumlah_kembalian' => $jumlah_kembalian,
                'pembayaran' => $request->input('pembayaran')
            ]);
            
            Session::flash('success', 'Titip Jual Barang Berhasil');
        } else {
            Session::flash('failed', 'Daftar Titip Jual Kosong');
        }

        return view('toko.transaksi.titip_jual.index', compact('barang', 'cur_date', 'data_notified', 'data_notif', 'kode_barang', 'kode_supplier', 'pembayaran', 'supplier'));
    }

    public function delete($id) {
        TitipJualDetailModel::where('id', $id)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function cancel(Request $request) {
        $nomor = $request->input('nomor');

        TitipJualModel::where('nomor', $nomor)->delete();
        TitipJualDetailModel::where('nomor', $nomor)->delete();
        
        return response()->json(['code'=>200]);
    }
}