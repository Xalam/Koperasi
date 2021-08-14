<?php

namespace App\Http\Controllers\Toko\Master\Barang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Barang\BarangModel;
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

        for ($i = 1; $i <= 20; $i++) {
            if ($i < 10) {
                $rak["0" . $i] = "0" . $i;
            } else {
                $rak[$i] = $i;
            }
        }
        
        return view('toko.master.barang.create', compact('data_notified', 'data_notif', 'data_notif_hutang', 'rak', 'supplier', 'tahun'));
    }

    public function store(Request $request) {
        $supplier[""] = "-- Pilih Supplier --";
        $tahun[""] = "";
        $rak[""] = "";
        
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
                'nomor_rak' => $request->input('nomor_rak'),
                'tingkat_rak' => $request->input('tingkat_rak'),
                'posisi_rak' => $request->input('posisi_rak'),
                'foto' => $request->input('nama') .'.' . $request->file('foto')->getClientOriginalExtension(),
                'expired_bulan' => $request->input('bulan'),
                'expired_tahun' => $request->input('tahun')
            ]);
    
            if ($request->file('foto')->isValid()) {
                $request->file('foto')->move(public_path('storage/toko/barang/foto/'), $request->input('nama') .'.' . $request->file('foto')->getClientOriginalExtension());
            }
            
            Session::flash('success', 'Berhasil');
        }

        return view('toko.master.barang.create', compact('data_notified', 'data_notif', 'rak', 'supplier', 'tahun'));
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