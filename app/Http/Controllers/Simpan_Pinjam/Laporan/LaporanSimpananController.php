<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Simpanan\SaldoTarik;
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
        $idAnggota = $request->id_anggota;

        $simpanan = Simpanan::where('id_anggota', $idAnggota)->where('status', 1)->get();
        $anggota = Anggota::findOrFail($idAnggota);

        $saldoTarik = SaldoTarik::with('saldo')
            ->whereHas('saldo', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
            })->where('status', 2)->get();

        if (sizeof($simpanan) == 0) {
            return redirect()->route('lap-simpanan.index')->with([
                'error' => 'Belum terdapat simpanan'
            ]);
        } else {
            return view('Simpan_Pinjam.laporan.simpanan.print', compact('simpanan', 'anggota', 'saldoTarik'));
        }
    }

    public function print_all()
    {
        $simpanan = Simpanan::where('status', 1)->get();
        $saldoTarik = SaldoTarik::with('saldo')->where('status', 2)->get();

        return view('Simpan_Pinjam.laporan.simpanan.print-all', compact('simpanan', 'saldoTarik'));
    }

    public function print_show($id)
    {
        $simpanan = Simpanan::where('id_anggota', $id)->where('status', 1)->get();
        $anggota = Anggota::findOrFail($id);

        $saldoTarik = SaldoTarik::with('saldo')
            ->whereHas('saldo', function ($query) use ($id) {
                $query->where('id_anggota', $id);
            })->where('status', 2)->get();

        return view('Simpan_Pinjam.laporan.simpanan.print-show', compact('simpanan', 'anggota', 'saldoTarik'));
    }
}
