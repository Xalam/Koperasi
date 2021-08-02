<?php

namespace App\Http\Controllers\Simpan_Pinjam\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Simpan_Pinjam\Utils\LaporanSHUAkun;
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
        $pinjaman   = Pinjaman::whereYear('tanggal', date('Y'))->where('status', 2)->get();
        $simpanan   = Simpanan::whereYear('tanggal', date('Y'))->where('status', 1)->get();
        $saldoTarik = SaldoTarik::whereYear('tanggal', date('Y'))->where('status', 2)->get();

        #SHU
        #Kode Akun 4XXX
        $idSimpanPinjam     = Akun::where('kode_akun', 4101)->first();
        $idUnitToko         = Akun::where('kode_akun', 4102)->first();
        $idFotoCopy         = Akun::where('kode_akun', 4103)->first();
        $idRuko             = Akun::where('kode_akun', 4104)->first();
        $idArisan           = Akun::where('kode_akun', 4105)->first();

        #Kode Akun 5XXX
        $idHPP              = Akun::where('kode_akun', 5101)->first();

        #Kode Akun 6XXX
        $idATK              = Akun::where('kode_akun', 6101)->first();
        $idBebanAset        = Akun::where('kode_akun', 6102)->first();
        $idKendaraan        = Akun::where('kode_akun', 6103)->first();
        $idInventaris       = Akun::where('kode_akun', 6104)->first();
        $idBangunan         = Akun::where('kode_akun', 6105)->first();
        $idBebanUsaha       = Akun::where('kode_akun', 6106)->first();
        $idBebanToko        = Akun::where('kode_akun', 6107)->first();
        $idBebanSP          = Akun::where('kode_akun', 6108)->first();
        $idBebanKonsumsi    = Akun::where('kode_akun', 6109)->first();
        $idBebanKebersihan  = Akun::where('kode_akun', 6110)->first();
        $idPajakPerson      = Akun::where('kode_akun', 6111)->first();
        $idPBB              = Akun::where('kode_akun', 6112)->first();
        $idAdminPajak       = Akun::where('kode_akun', 6201)->first();

        #Kode Akun 63XX
        $idDukunganPengurus = Akun::where('kode_akun', 6301)->first();
        $idPembinaan        = Akun::where('kode_akun', 6302)->first();
        $idDukunganJuru     = Akun::where('kode_akun', 6303)->first();
        $idListrik          = Akun::where('kode_akun', 6304)->first();
        $idPerjalananDinas  = Akun::where('kode_akun', 6305)->first();
        $idKasNegara        = Akun::where('kode_akun', 6306)->first();
        $idPromosi          = Akun::where('kode_akun', 6307)->first();
        $idKonsumsi         = Akun::where('kode_akun', 6308)->first();
        $idGotongRoyong     = Akun::where('kode_akun', 6309)->first();
        $idImbalanKerja     = Akun::where('kode_akun', 6310)->first();
        $idCinderaMata      = Akun::where('kode_akun', 6311)->first();

        #Kode Akun 64XX
        $idBebanRAT         = Akun::where('kode_akun', 6401)->first();

        #Kode Akun 42XX
        $idPendukungSHU     = Akun::where('kode_akun', 4201)->first();
        $idPendukungBunga   = Akun::where('kode_akun', 4202)->first();
        $idPendukungProvisi = Akun::where('kode_akun', 4203)->first();


        // $laba = JurnalUmum::selectRaw("sum(debet) as debet, sum(kredit) as kredit, DATE_FORMAT(tanggal, '%m') monthly")
        //     ->whereYear('tanggal', date('Y'))
        //     ->whereIn('id_akun', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
        //     ->orderBy('tanggal', 'ASC')
        //     ->groupBy('monthly')->get();

        $monthly = [];
        $countMonth = '';
        for ($i = 1; $i < 13; $i++) {
            $countMonth = str_pad($i, 2, '0', STR_PAD_LEFT);

            #Kode Akun 4XXX
            $akunFour = array(
                $idSimpanPinjam->id,
                $idUnitToko->id,
                $idFotoCopy->id,
                $idRuko->id,
                $idArisan->id
            );

            $valueAkunFour  = LaporanSHUAkun::dashboard($akunFour, $countMonth);
            $sumAkunFour    = LaporanSHUAkun::sum($valueAkunFour);

            #Kode Akun 5XXX
            $akunFive = array(
                $idHPP->id
            );

            $valueAkunFive  = LaporanSHUAkun::dashboard($akunFive, $countMonth);
            $sumAkunFive    = LaporanSHUAkun::sum($valueAkunFive);

            #Kode Akun 6XXX
            $akunSix = array(
                $idATK->id,
                $idBebanAset->id,
                $idKendaraan->id,
                $idInventaris->id,
                $idBangunan->id,
                $idBebanUsaha->id,
                $idBebanToko->id,
                $idBebanSP->id,
                $idBebanKonsumsi->id,
                $idBebanKebersihan->id,
                $idPajakPerson->id,
                $idPBB->id,
                $idAdminPajak->id
            );

            $valueAkunSix   = LaporanSHUAkun::dashboard($akunSix, $countMonth);
            $sumAkunSix     = LaporanSHUAkun::sum($valueAkunSix);

            #Kode Akun 63XX
            $akunSixThree = array(
                $idDukunganPengurus->id,
                $idPembinaan->id,
                $idDukunganJuru->id,
                $idListrik->id,
                $idPerjalananDinas->id,
                $idKasNegara->id,
                $idPromosi->id,
                $idKonsumsi->id,
                $idGotongRoyong->id,
                $idImbalanKerja->id,
                $idCinderaMata->id
            );

            $valueAkunSixThree  = LaporanSHUAkun::dashboard($akunSixThree, $countMonth);
            $sumAkunSixThree    = LaporanSHUAkun::sum($valueAkunSixThree);

            #Kode Akun 64XX
            $akunSixFour = array(
                $idBebanRAT->id
            );

            $valueAkunSixFour  = LaporanSHUAkun::dashboard($akunSixFour, $countMonth);
            $sumAkunSixFour    = LaporanSHUAkun::sum($valueAkunSixFour);

            #Kode Akun 42XX
            $akunFourTwo = array(
                $idPendukungSHU->id,
                $idPendukungBunga->id,
                $idPendukungProvisi->id
            );

            $valueAkunFourTwo  = LaporanSHUAkun::dashboard($akunFourTwo, $countMonth);
            $sumAkunFourTwo    = LaporanSHUAkun::sum($valueAkunFourTwo);

            //Kalkulasi
            $labaKotor = ($sumAkunFour * -1) - $sumAkunFive;
            $totalBeban = ($sumAkunSix + $sumAkunSixThree + $sumAkunSixFour) * -1;
            $pendapatanOperasional = $labaKotor + $totalBeban;

            $sumSHU = $pendapatanOperasional + ($sumAkunFourTwo * -1);

            // array_push($monthly, (object)['month' =>  date('Y-') . $countMonth, 'laba' => $sumSHU]);

            $monthly[] = [
                'month' => date('Y-') . $countMonth,
                'laba'  => $sumSHU
            ];
            // foreach ($laba as $key => $value) {
            //     $monthly[] = [
            //         'month' => date('Y-') . $countMonth,
            //         'laba'  => ($value->monthly != $countMonth) ? 0 : $value->kredit - $value->debet
            //     ];
            // }
        }

        // $count_laba     = $laba->sum('kredit') - $laba->sum('debet');
        $count_pinjaman = $pinjaman->sum('nominal_pinjaman');
        $count_simpanan = $simpanan->sum('nominal') - $saldoTarik->sum('nominal');
        $count_anggota  = $anggota->count();

        return view('Simpan_Pinjam.dashboard.dashboard', compact('count_anggota', 'count_pinjaman', 'count_simpanan', 'monthly'));
    }
}
