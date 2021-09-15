<?php

namespace App\Http\Controllers\Toko\Transaksi\ReturTitipJual;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Akun\AkunModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\ReturTitipJual\ReturTitipJualBarangModel;
use App\Models\Toko\Transaksi\ReturTitipJual\ReturTitipJualModel;
use App\Models\Toko\Transaksi\TitipJual\TitipJualModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ReturTitipJualController extends Controller
{
    public function index() {
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

        $cur_date = Carbon::now();
        
        $data_nomor_titip_jual = TitipJualModel::orderBy('nomor')->get();

        $nomor_titip_jual[''] = "-- Pilih Nomor Titip Jual --";
        foreach ($data_nomor_titip_jual as $data) {
            $nomor_titip_jual[$data->id] = $data->nomor;
        }

        return view('toko.transaksi.retur_titip_jual.index', compact('cur_date', 'data_notified', 'data_notif', 'data_notif_hutang', 'nomor_titip_jual'));
    }

    public function show($nomor) {
        $barang_retur = ReturTitipJualBarangModel::where('detail_retur_titip_jual.nomor', $nomor)
                                    ->join('barang', 'barang.id', '=', 'detail_retur_titip_jual.id_barang')
                                    ->select('detail_retur_titip_jual.id AS id', 'detail_retur_titip_jual.nomor AS nomor_retur', 'detail_retur_titip_jual.jumlah AS jumlah_retur',
                                            'detail_retur_titip_jual.total_harga AS total_harga', 'detail_retur_titip_jual.id_barang AS id_barang',
                                            'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                            'detail_retur_titip_jual.harga_beli AS harga_beli')
                                    ->get();

        $supplier_retur = ReturTitipJualModel::where('retur_titip_jual.nomor', $nomor)
                                    ->join('supplier', 'supplier.id', '=', 'retur_titip_jual.id_supplier')
                                    ->join('pembelian', 'pembelian.id', '=', 'retur_titip_jual.id_titip_jual')
                                    ->select('retur_titip_jual.tanggal AS tanggal', 'retur_titip_jual.nomor AS nomor_retur', 'pembelian.id AS id_beli',
                                            'pembelian.nomor AS nomor_beli', 'supplier.id AS id_supplier', 'supplier.nama AS nama_supplier')
                                    ->first();

        return response()->json(['code'=>200, 'barang_retur' => $barang_retur, 'supplier_retur' => $supplier_retur]);
    }

    public function store(Request $request) {
        $barang = ReturTitipJualBarangModel::where('nomor', $request->nomor)
                                        ->where('id_barang', $request->id_barang)->first();

        if ($barang) {
            ReturTitipJualBarangModel::where('nomor', $request->nomor)
                                        ->where('id_barang', $request->id_barang)->update([
                'jumlah' => $barang->jumlah + $request->jumlah, 
                'total_harga' => $barang->total_harga + $request->total_harga
                ]);
        } else {
            ReturTitipJualBarangModel::create($request->all());
        }
        
        return response()->json(['code'=>200]);
    }

    public function retur(Request $request) {
        $nomor = $request->input('nomor');
        $nomor_titip_jual = $request->input('nomor_titip_jual');

        $data_barang = ReturTitipJualBarangModel::where('nomor', $nomor)->get();

        if (count($data_barang) > 0) {
            ReturTitipJualBarangModel::where('nomor', $nomor)->update(['submited' => 1]);

            foreach ($data_barang as $data) {
                $barang = BarangModel::where('id', $data->id_barang)->first();

                BarangModel::where('id', $data->id_barang)->update([
                    'hpp' => ((($barang->stok_gudang + $barang->stok_etalase) * $barang->hpp) - $data->total_harga) / (($barang->stok_gudang + $barang->stok_etalase) - $data->jumlah),
                    'stok_gudang' => $barang->stok_gudang - $data->jumlah
                ]);
            }

            ReturTitipJualModel::create([
                'tanggal' => $request->input('tanggal'),
                'nomor' => $request->input('nomor'),
                'id_titip_jual' => $request->input('id_titip_jual'),
                'id_supplier' => $request->input('nama_supplier'),
                'jumlah_harga' => $request->input('jumlah_harga')
            ]);


            $keterangan = "Retur titip jual barang.";

            $persediaan = AkunModel::where('kode', 1131)->first();

            AkunModel::where('kode', 1131)->update([
                'debit' => $persediaan->debit - $request->input('jumlah_harga')
            ]);

            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $request->input('tanggal'),
                'keterangan' => $keterangan,
                'id_akun' => $persediaan->id,
                'debit' => 0,
                'kredit' => $request->input('jumlah_harga')
            ]); 

            $hutang = AkunModel::where('kode', 2102)->first();

            AkunModel::where('kode', 2102)->update([
                'kredit' => $hutang->kredit - $request->input('jumlah_harga')
            ]);

            $tanggal = Carbon::parse($request->input('tanggal'))->format('y-m-d');

            JurnalModel::create([
                'nomor' => $request->input('nomor_jurnal'),
                'tanggal' => $request->input('tanggal'),
                'keterangan' => $keterangan,
                'id_akun' => $hutang->id,
                'debit' => $request->input('jumlah_harga'),
                'kredit' => 0
            ]);
            
            Session::flash('success', 'Retur Titip Jual Berhasil');
        } else {
            Session::flash('failed', 'Daftar Retur Titip Jual Kosong');
        }

        $cur_date = "";
        $nomor_titip_jual = [];
        $data_notified = [];
        $data_notif = [];
        $data_notif_hutang = [];

        return view('toko.transaksi.retur_titip_jual.index', compact('cur_date', 'data_notified', 'data_notif', 'data_notif_hutang', 'nomor_titip_jual'));
    }

    public function delete($id) {
        ReturTitipJualBarangModel::where('id', $id)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function cancel(Request $request) {
        $nomor = $request->input('nomor');

        ReturTitipJualModel::where('nomor', $nomor)->delete();
        ReturTitipJualBarangModel::where('nomor', $nomor)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function nota() {
        $supplier = ReturTitipJualModel::leftJoin('supplier', 'supplier.id', '=', 'retur_titip_jual.id_supplier')
                                    ->select('supplier.kode AS kode_supplier', 'supplier.nama AS nama_supplier', 
                                            'supplier.alamat AS alamat_supplier', 'retur_titip_jual.*')
                                    ->orderBy('id', 'desc')
                                    ->limit(1)
                                    ->get();

        $last_nomor = ReturTitipJualModel::orderBy('id', 'desc')->first()->nomor;

        $pembelian = ReturTitipJualBarangModel::join('barang', 'barang.id', '=', 'detail_retur_titip_jual.id_barang')
                                            ->select('barang.nama AS nama_barang', 'barang.kode AS kode_barang', 'barang.satuan AS satuan', 
                                                    'detail_retur_titip_jual.jumlah AS jumlah', 'detail_retur_titip_jual.harga_beli AS harga_satuan', 
                                                    'detail_retur_titip_jual.total_harga AS total_harga')
                                            ->where('nomor', $last_nomor)->get();

        return view('toko.transaksi.retur_titip_jual.nota', compact('pembelian', 'supplier'));
    }
}