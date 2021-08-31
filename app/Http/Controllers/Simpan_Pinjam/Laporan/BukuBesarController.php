<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\JurnalUmum\JurnalUmumModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuBesarController extends Controller
{
    public function index()
    {
        $akun = Akun::get();

        return view('Simpan_Pinjam.laporan.buku-besar.index', compact('akun'));
    }

    public function show_data(Request $request)
    {
        $akun = Akun::findOrFail($request->id_akun);

        if ($request->start_date == '' && $request->end_date == '') {
            $a = JurnalUmumModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $akun->id);
            $b = JurnalModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $akun->id);
            $jurnal = JurnalUmum::select(DB::raw("id, CONVERT(kode_jurnal USING utf8) as kode_jurnal, id_akun, tanggal, CONVERT(keterangan USING utf8) as keterangan, debet, kredit"))->union($a)->union($b)->where('id_akun', $akun->id)->get();

            if (sizeof($jurnal) == 0) {
                return redirect()->route('buku-besar.index')->with([
                    'error' => 'Tidak terdapat data yang sesuai'
                ]);
            }

            return view('Simpan_Pinjam.laporan.buku-besar.show', compact('jurnal', 'akun'));
        } else {
            $reqStart   = $request->start_date;
            $reqEnd     = $request->end_date;
            $startDate  = date('Y-m-d', strtotime($reqStart));
            $endDate    = date('Y-m-d', strtotime($reqEnd));

            $a = JurnalUmumModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $akun->id)->whereBetween('tanggal', [$startDate, $endDate]);
            $b = JurnalModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $akun->id)->whereBetween('tanggal', [$startDate, $endDate]);
            $jurnal = JurnalUmum::select(DB::raw("id, CONVERT(kode_jurnal USING utf8) as kode_jurnal, id_akun, tanggal, CONVERT(keterangan USING utf8) as keterangan, debet, kredit"))->union($a)->union($b)->where('id_akun', $akun->id)->whereBetween('tanggal', [$startDate, $endDate])->get();

            if (sizeof($jurnal) == 0) {
                return redirect()->route('buku-besar.index')->with([
                    'error' => 'Tidak terdapat data yang sesuai'
                ]);
            }

            return view('Simpan_Pinjam.laporan.buku-besar.show', compact('jurnal', 'akun', 'reqStart', 'reqEnd'));
        }
    }

    public function print_show(Request $request)
    {
        $akun = Akun::findOrFail($request->id_akun);

        if ($request->start_date == '' && $request->end_date == '') {
            $reqStart   = '';
            $reqEnd     = date('d-m-Y');

            $a = JurnalUmumModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $akun->id);
            $b = JurnalModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $akun->id);
            $jurnal = JurnalUmum::select(DB::raw("id, CONVERT(kode_jurnal USING utf8) as kode_jurnal, id_akun, tanggal, CONVERT(keterangan USING utf8) as keterangan, debet, kredit"))->union($a)->union($b)->where('id_akun', $akun->id)->get();

            return view('Simpan_Pinjam.laporan.buku-besar.print-show', compact('jurnal', 'akun', 'reqStart', 'reqEnd'));
        } else {
            $reqStart   = $request->start_date;
            $reqEnd     = $request->end_date;
            $startDate  = date('Y-m-d', strtotime($reqStart));
            $endDate    = date('Y-m-d', strtotime($reqEnd));

            $a = JurnalUmumModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $akun->id)->whereBetween('tanggal', [$startDate, $endDate]);
            $b = JurnalModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $akun->id)->whereBetween('tanggal', [$startDate, $endDate]);
            $jurnal = JurnalUmum::select(DB::raw("id, CONVERT(kode_jurnal USING utf8) as kode_jurnal, id_akun, tanggal, CONVERT(keterangan USING utf8) as keterangan, debet, kredit"))->union($a)->union($b)->where('id_akun', $akun->id)->whereBetween('tanggal', [$startDate, $endDate])->get();

            return view('Simpan_Pinjam.laporan.buku-besar.print-show', compact('jurnal', 'akun', 'reqStart', 'reqEnd'));
        }
    }
}
