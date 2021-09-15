<?php

namespace App\Http\Controllers\Toko\Transaksi\Retur;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Toko\Master\Akun\AkunModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use App\Models\Toko\Transaksi\Retur\ReturPembelianBarangModel;
use App\Models\Toko\Transaksi\Retur\ReturPembelianModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ReturPembelianController extends Controller
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
        
        $data_nomor_beli = PembelianModel::orderBy('nomor')->get();

        $nomor_beli[''] = "-- Pilih Nomor Beli --";
        foreach ($data_nomor_beli as $data) {
            $nomor_beli[$data->id] = $data->nomor;
        }

        return view('toko.transaksi.retur.index', compact('cur_date', 'data_notified', 'data_notif', 'data_notif_hutang', 'nomor_beli'));
    }

    public function show($nomor) {
        $barang_retur = ReturPembelianBarangModel::where('detail_retur.nomor', $nomor)
                                    ->join('barang', 'barang.id', '=', 'detail_retur.id_barang')
                                    ->select('detail_retur.id AS id', 'detail_retur.nomor AS nomor_retur', 'detail_retur.jumlah AS jumlah_retur',
                                            'detail_retur.total_harga AS total_harga', 'detail_retur.id_barang AS id_barang',
                                            'barang.kode AS kode_barang', 'barang.nama AS nama_barang', 
                                            'detail_retur.harga_beli AS harga_beli')
                                    ->get();

        $supplier_retur = ReturPembelianModel::where('retur.nomor', $nomor)
                                    ->join('supplier', 'supplier.id', '=', 'retur.id_supplier')
                                    ->join('pembelian', 'pembelian.id', '=', 'retur.id_beli')
                                    ->select('retur.tanggal AS tanggal', 'retur.nomor AS nomor_retur', 'pembelian.id AS id_beli',
                                            'pembelian.nomor AS nomor_beli', 'supplier.id AS id_supplier', 'supplier.nama AS nama_supplier')
                                    ->first();

        return response()->json(['code'=>200, 'barang_retur' => $barang_retur, 'supplier_retur' => $supplier_retur]);
    }

    public function store(Request $request) {
        $barang = ReturPembelianBarangModel::where('nomor', $request->nomor)
                                        ->where('id_barang', $request->id_barang)->first();

        if ($barang) {
            ReturPembelianBarangModel::where('nomor', $request->nomor)
                                        ->where('id_barang', $request->id_barang)->update([
                'jumlah' => $barang->jumlah + $request->jumlah, 
                'total_harga' => $barang->total_harga + $request->total_harga
                ]);
        } else {
            ReturPembelianBarangModel::create($request->all());
        }
        
        return response()->json(['code'=>200]);
    }

    public function retur(Request $request) {
        $nomor = $request->input('nomor');
        $nomor_beli = $request->input('nomor_beli');

        $data_barang = ReturPembelianBarangModel::where('nomor', $nomor)->get();

        if (count($data_barang) > 0) {
            ReturPembelianBarangModel::where('nomor', $nomor)->update(['submited' => 1]);

            foreach ($data_barang as $data) {
                $barang = BarangModel::where('id', $data->id_barang)->first();

                BarangModel::where('id', $data->id_barang)->update([
                    'hpp' => ((($barang->stok_gudang + $barang->stok_etalase) * $barang->hpp) - $data->total_harga) / (($barang->stok_gudang + $barang->stok_etalase) - $data->jumlah),
                    'stok_gudang' => $barang->stok_gudang - $data->jumlah
                ]);
            }

            ReturPembelianModel::create([
                'tanggal' => $request->input('tanggal'),
                'nomor' => $request->input('nomor'),
                'id_beli' => $request->input('id_beli'),
                'id_supplier' => $request->input('nama_supplier'),
                'jumlah_harga' => $request->input('jumlah_harga')
            ]);

            $jenis_pembayaran = PembelianModel::where('id', $request->input('id_beli'))->first()->pembayaran;

            $keterangan = "Retur pembelian barang.";

            if ($jenis_pembayaran == 2) {
                $persediaan = AkunModel::where('kode', 1131)->first();
                $kas = AkunModel::where('kode', 1102)->first();

                AkunModel::where('kode', 1131)->update([
                    'debit' => $persediaan->debit - $request->input('jumlah_harga')
                ]);

                AkunModel::where('kode', 1102)->update([
                    'debit' => $kas->debit + $request->input('jumlah_harga')
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
                    'tanggal' => $request->input('tanggal'),
                    'keterangan' => $keterangan,
                    'id_akun' => $persediaan->id,
                    'debit' => 0,
                    'kredit' => $request->input('jumlah_harga')
                ]); 
            } else {
                $persediaan = AkunModel::where('kode', 1131)->first();
                $hutang = AkunModel::where('kode', 2101)->first();

                AkunModel::where('kode', 1131)->update([
                    'debit' => $persediaan->debit - $request->input('jumlah_harga')
                ]);

                AkunModel::where('kode', 2101)->update([
                    'kredit' => $hutang->kredit - $request->input('jumlah_harga')
                ]);

                $tanggal = Carbon::parse($request->input('tanggal'))->format('y-m-d');

                $data_hutang = HutangModel::where('nomor_beli', $nomor_beli)->first();

                HutangModel::where('nomor_beli', $nomor_beli)->update([
                    'jumlah_hutang' => $data_hutang->jumlah_hutang - $request->input('jumlah_harga'),
                    'sisa_hutang' => $data_hutang->sisa_hutang - $request->input('jumlah_harga')
                ]);
                    
                JurnalModel::create([
                    'nomor' => $request->input('nomor_jurnal'),
                    'tanggal' => $request->input('tanggal'),
                    'keterangan' => $keterangan,
                    'id_akun' => $hutang->id,
                    'debit' => $request->input('jumlah_harga'),
                    'kredit' => 0
                ]);

                JurnalModel::create([
                    'nomor' => $request->input('nomor_jurnal'),
                    'tanggal' => $request->input('tanggal'),
                    'keterangan' => $keterangan,
                    'id_akun' => $persediaan->id,
                    'debit' => 0,
                    'kredit' => $request->input('jumlah_harga')
                ]); 
            }
            
            Session::flash('success', 'Retur Barang Berhasil');
        } else {
            Session::flash('failed', 'Daftar Retur Pembelian Kosong');
        }

        $cur_date = "";
        $nomor_beli = [];
        $data_notified = [];
        $data_notif = [];
        $data_notif_hutang = [];

        return view('toko.transaksi.retur.index', compact('cur_date', 'data_notified', 'data_notif', 'data_notif_hutang', 'nomor_beli'));
    }

    public function delete($id) {
        ReturPembelianBarangModel::where('id', $id)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function cancel(Request $request) {
        $nomor = $request->input('nomor');

        ReturPembelianModel::where('nomor', $nomor)->delete();
        ReturPembelianBarangModel::where('nomor', $nomor)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function nota() {
        $supplier = ReturPembelianModel::leftJoin('supplier', 'supplier.id', '=', 'retur.id_supplier')
                                    ->select('supplier.kode AS kode_supplier', 'supplier.nama AS nama_supplier', 
                                            'supplier.alamat AS alamat_supplier', 'retur.*')
                                    ->orderBy('id', 'desc')
                                    ->limit(1)
                                    ->get();

        $last_nomor = ReturPembelianModel::orderBy('id', 'desc')->first()->nomor;

        $pembelian = ReturPembelianBarangModel::join('barang', 'barang.id', '=', 'detail_retur.id_barang')
                                            ->select('barang.nama AS nama_barang', 'barang.kode AS kode_barang', 'barang.satuan AS satuan', 
                                                    'detail_retur.jumlah AS jumlah', 'detail_retur.harga_beli AS harga_satuan', 
                                                    'detail_retur.total_harga AS total_harga')
                                            ->where('nomor', $last_nomor)->get();

        return view('toko.transaksi.retur.nota', compact('pembelian', 'supplier'));
    }
}
