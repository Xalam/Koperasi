<?php

namespace App\Http\Controllers\Toko\Transaksi\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Toko\Master\Akun\AkunModel;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\PembayaranModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianBarangModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PembelianController extends Controller
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
        return view('toko.transaksi.pembelian.index', compact('barang', 'cur_date', 'data_notified', 'data_notif', 'data_notif_hutang', 'kode_barang', 'kode_supplier', 'pembayaran', 'supplier'));
    }

    public function show($nomor) {
        $barang_pembelian = PembelianBarangModel::where('detail_beli.nomor', $nomor)
                                    ->join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                    ->select('detail_beli.id AS id', 'detail_beli.nomor AS nomor_pembelian', 'detail_beli.jumlah AS jumlah_barang',
                                            'detail_beli.total_harga AS total_harga', 'detail_beli.id_barang AS id_barang',
                                            'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                            'detail_beli.harga_satuan AS harga_satuan')
                                    ->get();

        $supplier_pembelian = PembelianModel::where('pembelian.nomor', $nomor)
                                    ->join('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                    ->select('pembelian.tanggal AS tanggal', 'pembelian.nomor AS nomor_pembelian', 'pembelian.id_supplier AS id_supplier',
                                            'pembelian.jumlah_bayar AS jumlah_bayar', 'pembelian.jumlah_kembalian AS jumlah_kembalian', 
                                            'pembelian.pembayaran AS pembayaran', 'supplier.alamat AS alamat', 'supplier.telepon AS telepon')
                                    ->first();

        return response()->json(['code'=>200, 'barang_pembelian' => $barang_pembelian, 'supplier_pembelian' => $supplier_pembelian]);
    }

    public function store(Request $request) {
        $barang = PembelianBarangModel::where('nomor', $request->nomor)
                                        ->where('id_barang', $request->id_barang)->first();

        if ($barang) {
            PembelianBarangModel::where('nomor', $request->nomor)
                                ->where('id_barang', $request->id_barang)->update([
                'harga_satuan' => ($barang->total_harga + ($request->jumlah * $request->harga_satuan)) / ($barang->jumlah + $request->jumlah), 
                'jumlah' => $barang->jumlah + $request->jumlah, 
                'total_harga' => $barang->total_harga + $request->total_harga
                ]);
        } else {
            PembelianBarangModel::create($request->all());
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
        $data_notif_hutang = [];

        $nomor = $request->input('nomor');

        PembelianBarangModel::where('nomor', $nomor)->update(['submited' => 1]);

        $data_barang = PembelianBarangModel::where('nomor', $nomor)->get();

        if (count($data_barang) > 0) {
            foreach ($data_barang as $data) {
                $barang = BarangModel::where('id', $data->id_barang)->first();
    
                BarangModel::where('id', $data->id_barang)->update([
                    'hpp' => ((($barang->stok_etalase + $barang->stok_gudang) * $barang->hpp) + $data->total_harga) / (($barang->stok_gudang + $barang->stok_etalase) + $data->jumlah),
                    'stok_gudang' => $barang->stok_gudang + $data->jumlah
                ]);
            }
    
            if ($request->input('pembayaran') == 2) {
                $persediaan = AkunModel::where('kode', 1131)->first();
                $kas = AkunModel::where('kode', 1102)->first();
    
                AkunModel::where('kode', 1131)->update([
                    'debit' => $persediaan->debit + $request->input('jumlah_harga')
                ]);
    
                AkunModel::where('kode', 1102)->update([
                    'debit' => $kas->debit - $request->input('jumlah_harga')
                ]);
    
                $keterangan = "Pembelian barang secara tunai.";
    
                JurnalModel::create([
                    'nomor' => $request->input('nomor_jurnal'),
                    'tanggal' => $request->input('tanggal'),
                    'keterangan' => $keterangan,
                    'id_akun' => $persediaan->id,
                    'debit' => $request->input('jumlah_harga'),
                    'kredit' => 0
                ]); 
    
                // JurnalUmum::create([
                //     'kode_jurnal' => $request->input('nomor_jurnal'),
                //     'id_akun' => $persediaan->id,
                //     'tanggal' => $request->input('tanggal'),
                //     'keterangan' => $keterangan,
                //     'debet' => $request->input('jumlah_harga'),
                //     'kredit' => 0
                // ]); 
                
                JurnalModel::create([
                    'nomor' => $request->input('nomor_jurnal'),
                    'tanggal' => $request->input('tanggal'),
                    'keterangan' => $keterangan,
                    'id_akun' => $kas->id,
                    'debit' => 0,
                    'kredit' => $request->input('jumlah_harga')
                ]);
                
                // JurnalUmum::create([
                //     'kode_jurnal' => $request->input('nomor_jurnal'),
                //     'id_akun' => $kas->id,
                //     'tanggal' => $request->input('tanggal'),
                //     'keterangan' => $keterangan,
                //     'debet' => 0,
                //     'kredit' => $request->input('jumlah_harga')
                // ]);
    
                $jumlah_bayar = $request->input('jumlah_bayar');
                $jumlah_kembalian = $request->input('jumlah_kembalian');
    
            } else {
                $persediaan = AkunModel::where('kode', 1131)->first();
                $hutang = AkunModel::where('kode', 2101)->first();
    
                AkunModel::where('kode', 1131)->update([
                    'debit' => $persediaan->debit + $request->input('jumlah_harga')
                ]);
    
                AkunModel::where('kode', 2101)->update([
                    'kredit' => $hutang->kredit + $request->input('jumlah_harga')
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
    
                $keterangan = "Pembelian barang secara kredit.";
    
                JurnalModel::create([
                    'nomor' => $request->input('nomor_jurnal'),
                    'tanggal' => $request->input('tanggal'),
                    'keterangan' => $keterangan,
                    'id_akun' => $persediaan->id,
                    'debit' => $request->input('jumlah_harga'),
                    'kredit' => 0
                ]); 
    
                // JurnalUmum::create([
                //     'kode_jurnal' => $request->input('nomor_jurnal'),
                //     'id_akun' => $persediaan->id,
                //     'tanggal' => $request->input('tanggal'),
                //     'keterangan' => $keterangan,
                //     'debet' => $request->input('jumlah_harga'),
                //     'kredit' => 0
                // ]); 
                    
                JurnalModel::create([
                    'nomor' => $request->input('nomor_jurnal'),
                    'tanggal' => $request->input('tanggal'),
                    'keterangan' => $keterangan,
                    'id_akun' => $hutang->id,
                    'debit' => 0,
                    'kredit' => $request->input('jumlah_harga')
                ]);
                    
                // JurnalUmum::create([
                //     'kode_jurnal' => $request->input('nomor_jurnal'),
                //     'id_akun' => $hutang->id,
                //     'tanggal' => $request->input('tanggal'),
                //     'keterangan' => $keterangan,
                //     'debet' => 0,
                //     'kredit' => $request->input('jumlah_harga')
                // ]);
            }
            
            PembelianModel::create([
                'tanggal' => $request->input('tanggal'),
                'nomor' => $request->input('nomor'),
                'nomor_jurnal' => $request->input('nomor_jurnal'),
                'id_supplier' => $request->input('kode_supplier'),
                'jumlah_harga' => $request->input('jumlah_harga'),
                'jumlah_bayar' => $jumlah_bayar,
                'jumlah_kembalian' => $jumlah_kembalian,
                'pembayaran' => $request->input('pembayaran')
            ]);
            
            Session::flash('success', 'Pembelian Barang Berhasil');
        } else {
            Session::flash('failed', 'Daftar Pembelian Kosong');
        }

        return view('toko.transaksi.pembelian.index', compact('barang', 'cur_date', 'data_notified', 'data_notif', 'data_notif_hutang', 'kode_barang', 'kode_supplier', 'pembayaran', 'supplier'));
    }

    public function delete($id) {
        PembelianBarangModel::where('id', $id)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function cancel(Request $request) {
        $nomor = $request->input('nomor');

        PembelianModel::where('nomor', $nomor)->delete();
        PembelianBarangModel::where('nomor', $nomor)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function nota() {
        $supplier = PembelianModel::leftJoin('supplier', 'supplier.id', '=', 'pembelian.id_supplier')
                                    ->select('supplier.kode AS kode_supplier', 'supplier.nama AS nama_supplier', 
                                            'supplier.alamat AS alamat_supplier', 'pembelian.*')
                                    ->orderBy('id', 'desc')
                                    ->limit(1)
                                    ->get();

        $last_nomor = PembelianModel::orderBy('id', 'desc')->first()->nomor;
                                    
        $pembayaran = PembelianModel::orderBy('id', 'desc')
                                    ->first();

        $pembelian = PembelianBarangModel::join('barang', 'barang.id', '=', 'detail_beli.id_barang')
                                            ->select('barang.nama AS nama_barang', 'barang.kode AS kode_barang', 'barang.satuan AS satuan', 
                                                    'detail_beli.jumlah AS jumlah', 'detail_beli.harga_satuan AS harga_satuan', 
                                                    'detail_beli.total_harga AS total_harga')
                                            ->where('nomor', $last_nomor)->get();

        return view('toko.transaksi.pembelian.nota', compact('supplier', 'pembelian', 'pembayaran'));
    }
}