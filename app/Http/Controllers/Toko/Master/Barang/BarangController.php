<?php

namespace App\Http\Controllers\Toko\Master\Barang;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangJenisModel;
use App\Models\Toko\Master\Barang\BarangKategoriModel;
use App\Models\Toko\Master\Barang\BarangKemasanModel;
use App\Models\Toko\Master\Barang\BarangKlasifikasiModel;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Master\Barang\BarangSatuanModel;
use App\Models\Toko\Master\Barang\BarangTeksturModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File; 

class BarangController extends Controller
{
    public function index() {
        $barang = BarangModel::join('supplier', 'supplier.id', '=', 'barang.id_supplier')
                                ->select('supplier.kode AS kode_supplier', 'supplier.nama AS nama_supplier', 
                                        'barang.*')
                                ->orderBy('barang.nama')
                                ->get();

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

        return view('toko.master.barang.index', compact('barang', 'data_notified', 'data_notif', 'data_notif_hutang'));
    }
    
    public function create() {
        $data_notif = BarangModel::where('alert_status', 1)->get();

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
        
        $cur_date = Carbon::now();

        HutangModel::where(DB::raw('DATE_ADD(DATE(NOW()), INTERVAL 3 DAY)'), '>=', DB::raw('DATE(jatuh_tempo)'))->update([
            'alert_status' => 1
        ]);

        $data_notif_hutang = HutangModel::join('supplier', 'supplier.id', '=', 'hutang.id_supplier')
                                    ->select('hutang.*', 'supplier.nama AS nama_supplier')
                                    ->get();

        for ($i = 0; $i < 10; $i++) {
            $tahun[substr(Carbon::now()->year + $i, 2, 2)] = Carbon::now()->year + $i;
        }

        $data_supplier = SupplierModel::all();

        $supplier[""] = "-- Pilih Supplier --";
        foreach ($data_supplier as $data) {
            $supplier[substr($data->kode, 5, 3)] = $data->nama;
        }

        $data_kategori_barang = BarangKategoriModel::all();

        if (count($data_kategori_barang) > 0){
            foreach ($data_kategori_barang as $data) {
                $kategori_barang[str_pad(strval($data->id), 2, '0', STR_PAD_LEFT)] = $data->nama;
            }
        } else {
            $kategori_barang[''] = "-- Kategori Tidak Tersedia --";
        }

        $data_jenis_barang = BarangJenisModel::all();


        if (count($data_jenis_barang) > 0){
            foreach ($data_jenis_barang as $data) {
                $jenis_barang[str_pad(strval($data->id), 2, '0', STR_PAD_LEFT)] = $data->nama;
            }
        } else {
            $jenis_barang[''] = "-- Jenis Tidak Tersedia --";
        }

        $data_tekstur_barang = BarangTeksturModel::all();

        if (count($data_tekstur_barang) > 0){
            foreach ($data_tekstur_barang as $data) {
                $tekstur_barang[str_pad(strval($data->id), 2, '0', STR_PAD_LEFT)] = $data->nama;
            }
        } else {
            $tekstur_barang[''] = "-- Tekstur Tidak Tersedia --";
        }

        $data_satuan_barang = BarangSatuanModel::all();

        if (count($data_satuan_barang) > 0){
            foreach ($data_satuan_barang as $data) {
                $satuan_barang[str_pad(strval($data->id), 2, '0', STR_PAD_LEFT)] = $data->nama;
            }
        } else {
            $satuan_barang[''] = "-- Satuan Tidak Tersedia --";
        }

        $data_kemasan_barang = BarangKemasanModel::all();

        if (count($data_kemasan_barang) > 0){
            foreach ($data_kemasan_barang as $data) {
                $kemasan_barang[str_pad(strval($data->id), 2, '0', STR_PAD_LEFT)] = $data->nama;
            }
        } else {
            $kemasan_barang[''] = "-- Kemasan Tidak Tersedia --";
        }
        
        return view('toko.master.barang.create', compact('cur_date', 'data_notified', 'data_notif', 'data_notif_hutang', 'kategori_barang', 'jenis_barang', 'tekstur_barang','satuan_barang', 'kemasan_barang', 'supplier', 'tahun'));
    }

    public function store(Request $request) {
        $supplier[""] = "-- Pilih Supplier --";
        $tahun[""] = "";
        $kategori_barang[""] = "";
        $jenis_barang[""] = "";
        $tekstur_barang[""] = "";
        $satuan_barang[""] = "";
        $kemasan_barang[""] = "";
        
        $data_notif = BarangModel::where('alert_status', 1)->get();

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
        
        $cur_date = Carbon::now();
        
        $id_supplier = SupplierModel::where('nama', $request->input('text_supplier'))->first()->id;

        $kodeExist = BarangModel::where('kode', $request->kode)->get();
        
        if (count($kodeExist) > 0) {
            Session::flash('failed', 'Kode barang sudah ada');
        } else {
            BarangModel::create([
                'kode' => $request->input('kode'),
                'nama' => $request->input('nama'),
                'id_supplier' => $id_supplier,
                'harga_jual' => $request->input('harga_jual'),
                'minimal_grosir' => $request->input('minimal_grosir'),
                'harga_grosir' => $request->input('harga_grosir'),
                'stok_minimal' => $request->input('stok_minimal'),
                'satuan' => $request->input('text_satuan'),
                'foto' => $request->input('nama') .'.' . $request->file('foto')->getClientOriginalExtension(),
                'tanggal_beli' => $request->input('tanggal_beli'),
                'expired_bulan' => $request->input('bulan'),
                'expired_tahun' => $request->input('tahun')
            ]);
    
            if ($request->file('foto')->isValid()) {
                $request->file('foto')->move(public_path('storage/toko/barang/foto/'), $request->input('nama') .'.' . $request->file('foto')->getClientOriginalExtension());
            }
            
            Session::flash('success', 'Berhasil');
        }

        return view('toko.master.barang.create', compact('cur_date', 'data_notified', 'data_notif', 'kategori_barang', 'jenis_barang', 'tekstur_barang','satuan_barang', 'kemasan_barang', 'supplier', 'tahun'));
    }

    public function update(Request $request) {
        BarangModel::where('id', $request->id)->update($request->all());

        $barang = BarangModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'barang' => $barang]);
    }

    public function delete(Request $request) {
        $oldBarang = BarangModel::where('id', $request->id)->first();
        
        File::delete(public_path('storage/toko/barang/foto/' . $oldBarang->foto));
        
        BarangModel::where('id', $request->id)->delete();

        $barang = BarangModel::where('id', $request->id)->first();

        return response()->json(['code' => 200, 'barang' => $barang]);
    }

    public function barcode($kode) {
        $data_notif = BarangModel::where('alert_status', 1)->get();

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
        
        $kode = $kode;

        return view('toko.master.barang.barcode', compact('kode', 'data_notif', 'data_notified'));
    }

    public function removeNotification($id) {
        BarangModel::where('id', $id)->update([
            'alert_status' => 0
        ]);
    }
}