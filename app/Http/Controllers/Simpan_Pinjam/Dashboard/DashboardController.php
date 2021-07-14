<?php

namespace App\Http\Controllers\Simpan_Pinjam\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use Illuminate\Http\Request;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use App\Models\Simpan_Pinjam\Simpanan\SaldoTarik;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;

class DashboardController extends Controller
{

    public function index()
    {
        $anggota    = Anggota::get();
        $pinjaman   = Pinjaman::whereYear('tanggal', date('Y'))->where('status', 1)->get();
        $simpanan   = Simpanan::whereYear('tanggal', date('Y'))->where('status', 1)->get();
        $saldoTarik = SaldoTarik::whereYear('tanggal', date('Y'))->where('status', 1)->get();

        $idSimpanPinjam = Akun::where('kode_akun', 4101)->first();
        $idUnitToko     = Akun::where('kode_akun', 4102)->first();
        $idFotoCopy     = Akun::where('kode_akun', 4103)->first();
        $idRuko         = Akun::where('kode_akun', 4104)->first();
        $idArisan       = Akun::where('kode_akun', 4104)->first();
        $idHPP          = Akun::where('kode_akun', 5101)->first();

        $laba = JurnalUmum::selectRaw("sum(debet) as debet, sum(kredit) as kredit, DATE_FORMAT(tanggal, '%m') monthly")
            ->whereYear('tanggal', date('Y'))
            ->whereIn('id_akun', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
            ->orderBy('tanggal', 'ASC')
            ->groupBy('monthly')->get();

        $monthly = [];
        $countMonth = '';
        for ($i = 1; $i < 13; $i++) {
            $countMonth = str_pad($i, 2, '0', STR_PAD_LEFT);

            foreach ($laba as $key => $value) {
                $monthly[] = [
                    'month' => date('Y-') . $countMonth,
                    'laba'  => ($value->monthly != $countMonth) ? 0 : $value->kredit - $value->debet
                ];
            }
        }

        $count_laba     = $laba->sum('kredit') - $laba->sum('debet');
        $count_pinjaman = $pinjaman->sum('nominal_pinjaman');
        $count_simpanan = $simpanan->sum('nominal') - $saldoTarik->sum('nominal');
        $count_anggota  = $anggota->count();

        return view('Simpan_Pinjam.dashboard.dashboard', compact('count_anggota', 'count_pinjaman', 'count_simpanan', 'count_laba', 'monthly'));
    }
}
