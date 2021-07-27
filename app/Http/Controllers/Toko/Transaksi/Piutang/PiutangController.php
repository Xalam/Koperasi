<?php

namespace App\Http\Controllers\Toko\Transaksi\Piutang;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Toko\Master\Akun\AkunModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\Piutang\PiutangDetailModel;
use App\Models\Toko\Transaksi\Piutang\PiutangModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PiutangController extends Controller
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

        $cur_date = Carbon::now();
        
        $data_piutang = PiutangModel::all();

        if (count($data_piutang) > 0) {
            $nomor[''] = "-- Pilih Nomor Piutang --";
            
            foreach ($data_piutang as $data) {
                $nomor[$data->id] = $data->nomor_jual;
            }
        } else {
            $nomor[''] = "-- Tidak Ada Data Piutang --";
        }

        $piutang = PiutangModel::join('tb_anggota', 'tb_anggota.id', '=', 'piutang.id_anggota')
                            ->select('piutang.*', 'tb_anggota.nama_anggota AS nama_anggota', 
                            'tb_anggota.kd_anggota AS kode_anggota', 'tb_anggota.limit_gaji AS limit_belanja')
                            ->get();

        return view('toko.transaksi.piutang.index', compact('cur_date', 'data_notified', 'data_notif', 'nomor', 'piutang'));
    }
    
    public function show($nomor_jual) {
        $terima_piutang = PiutangDetailModel::join('piutang', 'piutang.id', '=', 'terima_piutang.id_piutang')
                                            ->select('terima_piutang.*')
                                            ->where('id_piutang', $nomor_jual)
                                            ->get();

        $anggota_piutang = PiutangModel::join('tb_anggota', 'tb_anggota.id', '=', 'piutang.id_anggota')
                                    ->select('tb_anggota.nama_anggota AS nama_anggota', 'tb_anggota.id AS id_anggota', 
                                    'tb_anggota.kd_anggota AS kode_anggota', 'piutang.sisa_piutang AS sisa_piutang',
                                    'piutang.jumlah_piutang AS jumlah_piutang')
                                    ->where('piutang.id', $nomor_jual)
                                    ->first();

        return response()->json(['code'=>200, 'terima_piutang' => $terima_piutang, 'anggota_piutang' => $anggota_piutang]);
    }

    public function store(Request $request) {
        $id_piutang = $request->input('id_piutang');
        $terima_piutang = $request->input('terima_piutang');
        $sisa_piutang = $request->input('sisa_piutang');

        $jumlah_terima_piutang = PiutangModel::where('id', $id_piutang)->sum('jumlah_terima_piutang');

        PiutangModel::where('id', $id_piutang)->update([
            'jumlah_terima_piutang' => $jumlah_terima_piutang + $terima_piutang,
            'sisa_piutang' => $sisa_piutang
        ]);
        
        $data_piutang = PiutangModel::where('id', $id_piutang)->first();

        if ($data_piutang->sisa_piutang == 0) {
            PiutangModel::where('id', $id_piutang)->update([
                'status' => 1
            ]);
        }


        $kas = AkunModel::where('kode', 1102)->first();

        AkunModel::where('kode', 1102)->update([
            'debit' => $kas->debit + $terima_piutang
        ]);

        $piutang = AkunModel::where('kode', 1122)->first();

        AkunModel::where('kode', 1122)->update([
            'debit' => $piutang->debit - $terima_piutang
        ]);

        PiutangDetailModel::create($request->all());
            
        $keterangan = "Penerimaan piutang.";

        JurnalModel::create([
            'nomor' => $request->input('nomor_jurnal'),
            'tanggal' => $request->input('tanggal'),
            'keterangan' => $keterangan,
            'id_akun' => $kas->id,
            'debit' => $request->input('terima_piutang'),
            'kredit' => 0
        ]); 

        JurnalUmum::create([
            'kode_jurnal' => $request->input('nomor_jurnal'),
            'id_akun' => $kas->id,
            'tanggal' => $request->input('tanggal'),
            'keterangan' => $keterangan,
            'debet' => $request->input('terima_piutang'),
            'kredit' => 0
        ]); 

        JurnalModel::create([
            'nomor' => $request->input('nomor_jurnal'),
            'tanggal' => $request->input('tanggal'),
            'keterangan' => $keterangan,
            'id_akun' => $piutang->id,
            'debit' => 0,
            'kredit' => $request->input('terima_piutang')
        ]); 

        JurnalUmum::create([
            'kode_jurnal' => $request->input('nomor_jurnal'),
            'id_akun' => $piutang->id,
            'tanggal' => $request->input('tanggal'),
            'keterangan' => $keterangan,
            'debet' => 0,
            'kredit' => $request->input('terima_piutang')
        ]); 
        
        return response()->json(['code'=>200, 'message' => 'Pembayaran Piutang Berhasil']);
    }

    public function delete($id) {
        $data_terima_piutang = PiutangDetailModel::where('id', $id)->first();
        $data_piutang = PiutangModel::where('id', $data_terima_piutang->id_piutang)->first();

        PiutangModel::where('id', $data_terima_piutang->id_piutang)->update([
            'jumlah_terima_piutang' => $data_piutang->jumlah_terima_piutang - $data_terima_piutang->terima_piutang
        ]);
        
        $data_piutang = PiutangModel::where('id', $data_terima_piutang->id_piutang)->first();

        PiutangModel::where('id', $data_terima_piutang->id_piutang)->update([
            'sisa_piutang' => $data_piutang->jumlah_piutang - $data_piutang->jumlah_terima_piutang
        ]);
        
        $data_piutang = PiutangModel::where('id', $data_terima_piutang->id_piutang)->first();

        if ($data_piutang->sisa_piutang > 0) {
            PiutangModel::where('id', $data_terima_piutang->id_piutang)->update([
                'status' => 0
            ]);
        }

        PiutangDetailModel::where('id', $id)->delete();
        JurnalModel::where('nomor', $data_terima_piutang->nomor_jurnal)->delete();
        JurnalUmum::where('kode_jurnal', $data_terima_piutang->nomor_jurnal)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function cancel(Request $request) {
        return response()->json(['code'=>200]);
    }
}
