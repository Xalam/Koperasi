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

        return view('Simpan_Pinjam.laporan.pinjaman.index', compact('anggota'));
    }

    public function show(Request $request)
    {
        $pinjaman = Pinjaman::where('id_anggota', $request->id_anggota)->where('status', 2)->get();
        $anggota = Anggota::findOrFail($request->id_anggota);

        if (sizeof($pinjaman) == 0) {
            return redirect()->route('lap-pinjaman.index')->with([
                'error' => 'Belum terdapat pinjaman'
            ]);
        } else {
            $sumPinjaman = $pinjaman->sum('nominal_pinjaman');

            return view('Simpan_Pinjam.laporan.pinjaman.print', compact('pinjaman', 'anggota', 'sumPinjaman'));
        }
    }

    public function print_all()
    {
        $pinjaman = Pinjaman::where('status', 2)->get();
        $sumPinjaman = $pinjaman->sum('nominal_pinjaman');

        return view('Simpan_Pinjam.laporan.pinjaman.print-all', compact('pinjaman', 'sumPinjaman'));
    }

    public function print_show($id)
    {
        $pinjaman = Pinjaman::where('id_anggota', $id)->where('status', 2)->get();
        $anggota = Anggota::findOrFail($id);

        $sumPinjaman = $pinjaman->sum('nominal_pinjaman');

        return view('Simpan_Pinjam.laporan.pinjaman.print-show', compact('pinjaman', 'anggota', 'sumPinjaman'));
    }
}
