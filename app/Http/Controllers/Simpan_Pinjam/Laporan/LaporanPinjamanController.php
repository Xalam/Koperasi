<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;
use Illuminate\Http\Request;

class LaporanPinjamanController extends Controller
{
    public function index()
    {
        $anggota = Anggota::get();

        return view('simpan_pinjam.laporan.pinjaman.index', compact('anggota'));
    }

    public function show(Request $request)
    {
        $pinjaman = Pinjaman::where('id_anggota', $request->id_anggota)->get();
        $anggota = Anggota::findOrFail($request->id_anggota);

        return view('simpan_pinjam.laporan.pinjaman.print', compact('pinjaman', 'anggota'));
    }

    public function print_all()
    {
        $pinjaman = Pinjaman::get();

        return view('simpan_pinjam.laporan.pinjaman.print-all', compact('pinjaman'));
    }

    public function print_show($id)
    {
        $pinjaman = Pinjaman::where('id_anggota', $id)->get();
        $anggota = Anggota::findOrFail($id);

        return view('simpan_pinjam.laporan.pinjaman.print-show', compact('pinjaman', 'anggota'));
    }
}
