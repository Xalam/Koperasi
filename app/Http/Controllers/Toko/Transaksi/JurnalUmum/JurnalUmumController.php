<?php

namespace App\Http\Controllers\Toko\Transaksi\JurnalUmum;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Akun\AkunJenisModel;
use App\Models\Toko\Master\Akun\AkunModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\JurnalUmum\JurnalUmumDetailModel;
use App\Models\Toko\Transaksi\JurnalUmum\JurnalUmumModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JurnalUmumController extends Controller
{
    public function index() {
        $jurnal_umum = JurnalUmumModel::all();

        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            }
        }

        return view('toko.transaksi.jurnal_umum.index', compact('jurnal_umum', 'data_notified', 'data_notif'));
    }

    public function show($nomor) {
        $jurnal_umum = JurnalUmumDetailModel::where('detail_jurnal_umum.nomor', $nomor)
                                    ->join('akun', 'akun.id', '=', 'detail_jurnal_umum.id_akun')
                                    ->select('detail_jurnal_umum.*', 'akun.kode AS kode_akun', 'akun.nama AS nama_akun')
                                    ->get();

        return response()->json(['code'=>200, 'jurnal_umum' => $jurnal_umum]);
    }
    
    public function create() {
        $cur_date = Carbon::now();

        $data_notif = BarangModel::where('alert_status', 1)->get();

        $data_notified = BarangModel::all();
        foreach ($data_notified AS $data) {
            if ($data->stok <= $data->stok_minimal) {
                BarangModel::where('id', $data->id)->update([
                    'alert_status' => 1
                ]);
            }
        }

        $data_akun = AkunModel::orderBy('kode')->get();

        $kode_akun[''] = "-- Pilih Kode Akun --";
        foreach ($data_akun as $data) {
            $kode_akun[$data->id] = $data->kode;
        }

        $data_akun = AkunModel::orderBy('nama')->get();

        $nama_akun[''] = "-- Pilih Nama Akun --";
        foreach ($data_akun as $data) {
            $nama_akun[$data->id] = $data->nama;
        }

        return view('toko.transaksi.jurnal.create', compact('cur_date', 'data_notified', 'data_notif', 'kode_akun', 'nama_akun'));
    }

    public function store(Request $request) {
        $jurnal_umum = JurnalUmumDetailModel::where('nomor', $request->nomor)
                                            ->where('id_akun', $request->id_akun)->first();

        if ($jurnal_umum) {
            JurnalUmumDetailModel::where('nomor', $request->nomor)
                                ->where('id_akun', $request->id_akun)->update([
                'debit' => $jurnal_umum->debit + $request->debit, 
                'kredit' => $jurnal_umum->kredit + $request->kredit
                ]);
        } else {
            JurnalUmumDetailModel::create($request->all());
        }

        return response()->json(['code'=>200]);
    }

    public function save(Request $request) {
        $nomor = $request->input('nomor');

        JurnalUmumDetailModel::where('nomor', $nomor)->update(['submited' => 1]);

        $data_jurnal_umum = JurnalUmumDetailModel::where('detail_jurnal_umum.nomor', $nomor)
                                                ->join('akun', 'akun.id', '=', 'detail_jurnal_umum.id_akun')
                                                ->select('detail_jurnal_umum.*', 'akun.kode AS kode_akun', 'akun.nama AS nama_akun')
                                                ->get();

        foreach ($data_jurnal_umum as $data) {
            $akun = AkunModel::where('id', $data->id_akun)->first();
            $jenis_akun = AkunJenisModel::where('id', substr($data->kode_akun, 0, 1))->first();

            if ($jenis_akun->nama == "Debit") {
                AkunModel::where('kode', $data->kode_akun)->update([
                    'debit' => $akun->debit + $data->debit - $data->kredit
                ]);
            } else {
                AkunModel::where('kode', $data->kode_akun)->update([
                    'kredit' => $akun->kredit - $data->debit + $data->kredit
                ]);
            }

            JurnalModel::create([
                'nomor' => $data->nomor,
                'tanggal' => $request->input('tanggal'),
                'keterangan' => $request->input('keterangan'),
                'id_akun' => $data->id_akun,
                'debit' => $data->debit,
                'kredit' => $data->kredit
            ]);
        } 

        $debit = JurnalUmumDetailModel::where('nomor', $nomor)->sum('debit');
        $kredit = JurnalUmumDetailModel::where('nomor', $nomor)->sum('kredit');

        JurnalUmumModel::create([
            'nomor' => $data->nomor,
            'tanggal' => $request->input('tanggal'),
            'keterangan' => $request->input('keterangan'),
            'debit' => $debit,
            'kredit' => $kredit
        ]);
        
        return redirect('toko/transaksi/jurnal');
    }

    public function delete($id) {
        JurnalUmumDetailModel::where('id', $id)->delete();
        
        return response()->json(['code'=>200]);
    }

    public function cancel(Request $request) {
        $nomor = $request->input('nomor');

        JurnalUmumModel::where('nomor', $nomor)->delete();
        JurnalUmumDetailModel::where('nomor', $nomor)->delete();
        
        return response()->json(['code'=>200]);
    }
}
