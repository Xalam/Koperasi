<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Simpan_Pinjam\Utils\LaporanSHUAkun;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use Illuminate\Http\Request;

class EkuitasController extends Controller
{
    public function index()
    {
        return view('Simpan_Pinjam.laporan.ekuitas.index');
    }

    public function show_data(Request $request)
    {
        #Akun
        $idPokok    = Akun::where('kode_akun', 3101)->first();
        $idWajib    = Akun::where('kode_akun', 3102)->first();
        $idInkoppol = Akun::where('kode_akun', 3111)->first();
        $idDinas    = Akun::where('kode_akun', 3112)->first();
        $idSimsus   = Akun::where('kode_akun', 3113)->first();
        $idCadangan = Akun::where('kode_akun', 3121)->first();
        $idResiko   = Akun::where('kode_akun', 3122)->first();
        $idSHU      = Akun::where('kode_akun', 3131)->first();

        //SHU
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

        $saldoAkhir = array();

        $totalSaldo = $idPokok->saldo + $idWajib->saldo + $idInkoppol->saldo + $idDinas->saldo +
            $idSimsus->saldo + $idCadangan->saldo + $idResiko->saldo + $idSHU->saldo;

        $akun = array(
            $idPokok, $idWajib, $idInkoppol, $idDinas, $idSimsus, $idCadangan, $idResiko, $idSHU
        );

        $akunSaldo = array($idPokok->saldo * -1, $idWajib->saldo * -1, $idInkoppol->saldo * -1, $idDinas->saldo * -1, $idSimsus->saldo * -1, $idCadangan->saldo * -1, $idResiko->saldo * -1, $idSHU->saldo * -1, $totalSaldo * -1);

        $startDate = '';
        $endDate   = date('d-m-Y');

        $reqStart = '';
        $reqEnd = date('Y-m-d');

        if ($request->start_date != '' && $request->end_date != '') {

            $reqStart = date('Y-m-d', strtotime($request->start_date));
            $reqEnd = date('Y-m-d', strtotime($request->end_date));

            $startDate = date('d-m-Y', strtotime($request->start_date));
            $endDate = date('d-m-Y', strtotime($request->end_date));
        }

        $pokok      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idPokok->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $wajib      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idWajib->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $inkoppol   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idInkoppol->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $dinas      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idDinas->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $simsus     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idSimsus->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $cadangan   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idCadangan->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $resiko     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idResiko->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

        #SHU
        #Kode Akun 4XXX
        $akunFour = array(
            $idSimpanPinjam->id,
            $idUnitToko->id,
            $idFotoCopy->id,
            $idRuko->id,
            $idArisan->id
        );

        $valueAkunFour  = LaporanSHUAkun::show($akunFour, $reqStart, $reqEnd);
        $sumAkunFour    = LaporanSHUAkun::sum($valueAkunFour);

        #Kode Akun 5XXX
        $akunFive = array(
            $idHPP->id
        );

        $valueAkunFive  = LaporanSHUAkun::show($akunFive, $reqStart, $reqEnd);
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

        $valueAkunSix   = LaporanSHUAkun::show($akunSix, $reqStart, $reqEnd);
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

        $valueAkunSixThree  = LaporanSHUAkun::show($akunSixThree, $reqStart, $reqEnd);
        $sumAkunSixThree    = LaporanSHUAkun::sum($valueAkunSixThree);

        #Kode Akun 64XX
        $akunSixFour = array(
            $idBebanRAT->id
        );

        $valueAkunSixFour  = LaporanSHUAkun::show($akunSixFour, $reqStart, $reqEnd);
        $sumAkunSixFour    = LaporanSHUAkun::sum($valueAkunSixFour);

        #Kode Akun 42XX
        $akunFourTwo = array(
            $idPendukungSHU->id,
            $idPendukungBunga->id,
            $idPendukungProvisi->id
        );

        $valueAkunFourTwo  = LaporanSHUAkun::show($akunFourTwo, $reqStart, $reqEnd);
        $sumAkunFourTwo    = LaporanSHUAkun::sum($valueAkunFourTwo);

        //Kalkulasi SHU
        $labaKotor = ($sumAkunFour * -1) - $sumAkunFive;
        $totalBeban = ($sumAkunSix + $sumAkunSixThree + $sumAkunSixFour) * -1;
        $pendapatanOperasional = $labaKotor + $totalBeban;

        $sumSHU = $pendapatanOperasional + ($sumAkunFourTwo * -1);

        $shuKredit = 0;
        $shuDebet  = 0;

        if ($sumSHU < 0) {
            $shuDebet = $sumSHU * -1;
        } else {
            $shuKredit = $sumSHU;
        }

        #Ekuitas
        $totalPenambahan   = $pokok->kredit + $wajib->kredit + $inkoppol->kredit + $dinas->kredit +
            $simsus->kredit + $cadangan->kredit + $resiko->kredit + $shuKredit;
        $totalPengurangan  = $pokok->debet + $wajib->debet + $inkoppol->debet + $dinas->debet +
            $simsus->debet + $cadangan->debet + $resiko->debet + $shuDebet;

        $penambahan   = array($pokok->kredit, $wajib->kredit, $inkoppol->kredit, $dinas->kredit, $simsus->kredit, $cadangan->kredit, $resiko->kredit, $shuKredit, $totalPenambahan);
        $pengurangan  = array($pokok->debet, $wajib->debet, $inkoppol->debet, $dinas->debet, $simsus->debet, $cadangan->debet, $resiko->debet, $shuDebet, $totalPengurangan);

        for ($i = 0; $i < sizeof($akunSaldo); $i++) {
            array_push($saldoAkhir, $akunSaldo[$i] - $pengurangan[$i] + $penambahan[$i]);
        }

        return view('Simpan_Pinjam.laporan.ekuitas.show', compact('akun', 'pengurangan', 'penambahan', 'totalSaldo', 'totalPengurangan', 'totalPenambahan', 'saldoAkhir', 'startDate', 'endDate'));
    }

    public function print_show(Request $request)
    {
        #Akun
        $idPokok    = Akun::where('kode_akun', 3101)->first();
        $idWajib    = Akun::where('kode_akun', 3102)->first();
        $idInkoppol = Akun::where('kode_akun', 3111)->first();
        $idDinas    = Akun::where('kode_akun', 3112)->first();
        $idSimsus   = Akun::where('kode_akun', 3113)->first();
        $idCadangan = Akun::where('kode_akun', 3121)->first();
        $idResiko   = Akun::where('kode_akun', 3122)->first();
        $idSHU      = Akun::where('kode_akun', 3131)->first();

        //SHU
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

        $saldoAkhir = array();

        $totalSaldo = $idPokok->saldo + $idWajib->saldo + $idInkoppol->saldo + $idDinas->saldo +
            $idSimsus->saldo + $idCadangan->saldo + $idResiko->saldo + $idSHU->saldo;

        $akun = array(
            $idPokok, $idWajib, $idInkoppol, $idDinas, $idSimsus, $idCadangan, $idResiko, $idSHU
        );

        $akunSaldo = array($idPokok->saldo * -1, $idWajib->saldo * -1, $idInkoppol->saldo * -1, $idDinas->saldo * -1, $idSimsus->saldo * -1, $idCadangan->saldo * -1, $idResiko->saldo * -1, $idSHU->saldo * -1, $totalSaldo * -1);

        $startDate = '';
        $endDate   = date('d-m-Y');

        $reqStart = '';
        $reqEnd = date('Y-m-d');

        if ($request->start_date != '' && $request->end_date != '') {

            $reqStart = date('Y-m-d', strtotime($request->start_date));
            $reqEnd = date('Y-m-d', strtotime($request->end_date));

            $startDate = date('d-m-Y', strtotime($request->start_date));
            $endDate = date('d-m-Y', strtotime($request->end_date));
        }

        $pokok      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idPokok->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $wajib      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idWajib->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $inkoppol   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idInkoppol->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $dinas      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idDinas->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $simsus     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idSimsus->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $cadangan   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idCadangan->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();
        $resiko     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idResiko->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

        #SHU
        #Kode Akun 4XXX
        $akunFour = array(
            $idSimpanPinjam->id,
            $idUnitToko->id,
            $idFotoCopy->id,
            $idRuko->id,
            $idArisan->id
        );

        $valueAkunFour  = LaporanSHUAkun::show($akunFour, $reqStart, $reqEnd);
        $sumAkunFour    = LaporanSHUAkun::sum($valueAkunFour);

        #Kode Akun 5XXX
        $akunFive = array(
            $idHPP->id
        );

        $valueAkunFive  = LaporanSHUAkun::show($akunFive, $reqStart, $reqEnd);
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

        $valueAkunSix   = LaporanSHUAkun::show($akunSix, $reqStart, $reqEnd);
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

        $valueAkunSixThree  = LaporanSHUAkun::show($akunSixThree, $reqStart, $reqEnd);
        $sumAkunSixThree    = LaporanSHUAkun::sum($valueAkunSixThree);

        #Kode Akun 64XX
        $akunSixFour = array(
            $idBebanRAT->id
        );

        $valueAkunSixFour  = LaporanSHUAkun::show($akunSixFour, $reqStart, $reqEnd);
        $sumAkunSixFour    = LaporanSHUAkun::sum($valueAkunSixFour);

        #Kode Akun 42XX
        $akunFourTwo = array(
            $idPendukungSHU->id,
            $idPendukungBunga->id,
            $idPendukungProvisi->id
        );

        $valueAkunFourTwo  = LaporanSHUAkun::show($akunFourTwo, $reqStart, $reqEnd);
        $sumAkunFourTwo    = LaporanSHUAkun::sum($valueAkunFourTwo);

        //Kalkulasi SHU
        $labaKotor = ($sumAkunFour * -1) - $sumAkunFive;
        $totalBeban = ($sumAkunSix + $sumAkunSixThree + $sumAkunSixFour) * -1;
        $pendapatanOperasional = $labaKotor + $totalBeban;

        $sumSHU = $pendapatanOperasional + ($sumAkunFourTwo * -1);

        $shuKredit = 0;
        $shuDebet  = 0;

        if ($sumSHU < 0) {
            $shuDebet = $sumSHU * -1;
        } else {
            $shuKredit = $sumSHU;
        }

        #Ekuitas
        $totalPenambahan   = $pokok->kredit + $wajib->kredit + $inkoppol->kredit + $dinas->kredit +
            $simsus->kredit + $cadangan->kredit + $resiko->kredit + $shuKredit;
        $totalPengurangan  = $pokok->debet + $wajib->debet + $inkoppol->debet + $dinas->debet +
            $simsus->debet + $cadangan->debet + $resiko->debet + $shuDebet;

        $penambahan   = array($pokok->kredit, $wajib->kredit, $inkoppol->kredit, $dinas->kredit, $simsus->kredit, $cadangan->kredit, $resiko->kredit, $shuKredit, $totalPenambahan);
        $pengurangan  = array($pokok->debet, $wajib->debet, $inkoppol->debet, $dinas->debet, $simsus->debet, $cadangan->debet, $resiko->debet, $shuDebet, $totalPengurangan);

        for ($i = 0; $i < sizeof($akunSaldo); $i++) {
            array_push($saldoAkhir, $akunSaldo[$i] - $pengurangan[$i] + $penambahan[$i]);
        }

        return view('Simpan_Pinjam.laporan.ekuitas.print-show', compact('akun', 'pengurangan', 'penambahan', 'totalSaldo', 'totalPengurangan', 'totalPenambahan', 'saldoAkhir', 'startDate', 'endDate'));
    }
}
