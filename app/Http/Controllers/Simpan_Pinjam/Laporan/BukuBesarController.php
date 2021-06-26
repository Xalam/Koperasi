<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use Illuminate\Http\Request;

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
            $jurnal = JurnalUmum::where('id_akun', $request->id_akun)->get();

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

            $jurnal = JurnalUmum::where('id_akun', $request->id_akun)->whereBetween('tanggal', [$startDate, $endDate])->get();

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

            $jurnal = JurnalUmum::where('id_akun', $request->id_akun)->get();

            return view('Simpan_Pinjam.laporan.buku-besar.print-show', compact('jurnal', 'akun', 'reqStart', 'reqEnd'));

        } else {
            $reqStart   = $request->start_date;
            $reqEnd     = $request->end_date;
            $startDate  = date('Y-m-d', strtotime($reqStart));
            $endDate    = date('Y-m-d', strtotime($reqEnd));

            $jurnal = JurnalUmum::where('id_akun', $request->id_akun)->whereBetween('tanggal', [$startDate, $endDate])->get();

            return view('Simpan_Pinjam.laporan.buku-besar.print-show', compact('jurnal', 'akun', 'reqStart', 'reqEnd'));
        }
    }
}
