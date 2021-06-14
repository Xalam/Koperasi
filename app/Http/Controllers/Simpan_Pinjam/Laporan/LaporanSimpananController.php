<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;
use Illuminate\Http\Request;

class LaporanSimpananController extends Controller
{
    public function index()
    {
        $anggota = Anggota::get();

        return view('Simpan_Pinjam.laporan.simpanan.index', compact('anggota'));
    }

    public function show(Request $request)
    {
        $simpanan = Simpanan::where('id_anggota', $request->id_anggota)->get();
        $anggota = Anggota::findOrFail($request->id_anggota);

        return view('Simpan_Pinjam.laporan.simpanan.print', compact('simpanan', 'anggota'));
    }

    public function print_all()
    {
        $simpanan = Simpanan::get();

        return view('Simpan_Pinjam.laporan.simpanan.print-all', compact('simpanan'));
    }

    public function print_show($id)
    {
        $simpanan = Simpanan::where('id_anggota', $id)->get();
        $anggota = Anggota::findOrFail($id);

        return view('Simpan_Pinjam.laporan.simpanan.print-show', compact('simpanan', 'anggota'));
    }
}
