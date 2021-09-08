<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Simpan_Pinjam\Utils\LaporanSHUAkun;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Simpan_Pinjam\Pengaturan\PembagianSHU;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\JurnalUmum\JurnalUmumModel;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index()
    {
        return view('Simpan_Pinjam.laporan.keuangan.index');
    }

    public function show_data(Request $request)
    {
        $asetLancar         = Akun::whereIn('kode_akun', [1101, 1102, 1111, 1112, 1121, 1122, 1123, 1124, 1125, 1126, 1127, 1131, 1141])->select('id', 'nama_akun', 'saldo')->get();
        $penyertaan         = Akun::whereIn('kode_akun', [1201, 1202])->select('id', 'nama_akun', 'saldo')->get();
        $asetTidakLancar    = Akun::whereIn('kode_akun', [1301, 1302, 1311, 1321, 1312, 1322, 1313, 1323, 1314, 1324, 1315, 1325, 1316, 1326])->select('id', 'nama_akun', 'saldo')->get();
        $kewajibanPendek    = Akun::whereIn('kode_akun', [2101, 2102, 2111, 2112, 2113, 2114, 2115, 2116, 2117, 2121])->select('id', 'nama_akun', 'saldo')->get();
        $kewajibanPanjang   = Akun::whereIn('kode_akun', [2201])->select('id', 'nama_akun', 'saldo')->get();
        $ekuitas            = Akun::whereIn('kode_akun', [3101, 3102, 3111, 3112, 3113, 3121, 3122])->select('id', 'nama_akun', 'saldo')->get();

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

        $saldoLancar      = array();
        $saldoPenyertaan  = array();
        $saldoTidakLancar = array();
        $saldoPendek      = array();
        $saldoPanjang     = array();
        $saldoEkuitas     = array();

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

        //Kalkulasi
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

        #Sum SHU Akun
        $shuAkunSaldo = Akun::where('kode_akun', 3131)->first();
        $sumSHUAkun = $shuAkunSaldo->saldo - $shuKredit + $shuDebet;

        if ($sumSHUAkun < 0) {
            $sumSHUAkun = $sumSHUAkun * -1;
        }

        #Aset Lancar
        for ($i = 0; $i < sizeof($asetLancar); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $asetLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $asetLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $asetLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $asetLancar[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoLancar, $calculate);
        }

        #Penyertaan
        for ($i = 0; $i < sizeof($penyertaan); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $penyertaan[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $penyertaan[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $penyertaan[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $penyertaan[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $penyertaan[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoPenyertaan, $calculate);
        }

        #Aset Tidak Lancar
        for ($i = 0; $i < sizeof($asetTidakLancar); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $asetTidakLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $asetTidakLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $asetTidakLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetTidakLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $asetTidakLancar[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoTidakLancar, $calculate);
        }

        #Kewajiban Pendek
        for ($i = 0; $i < sizeof($kewajibanPendek); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $kewajibanPendek[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $kewajibanPendek[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $kewajibanPendek[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPendek[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $kewajibanPendek[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoPendek, $calculate);
        }

        #Kewajiban Panjang            
        for ($i = 0; $i < sizeof($kewajibanPanjang); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $kewajibanPanjang[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $kewajibanPanjang[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $kewajibanPanjang[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPanjang[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $kewajibanPanjang[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoPanjang, $calculate);
        }

        #Ekuitas
        for ($i = 0; $i < sizeof($ekuitas); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $ekuitas[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $ekuitas[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $ekuitas[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $ekuitas[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $ekuitas[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoEkuitas, $calculate);
        }

        $sumLancar      = array_sum($saldoLancar);
        $sumPenyertaan  = array_sum($saldoPenyertaan);
        $sumTidakLancar = array_sum($saldoTidakLancar);
        $sumPendek      = array_sum($saldoPendek);
        $sumPanjang     = array_sum($saldoPanjang);
        $sumEkuitas     = array_sum($saldoEkuitas);

        return view('Simpan_Pinjam.laporan.keuangan.show', compact(
            'asetLancar',
            'penyertaan',
            'asetTidakLancar',
            'kewajibanPendek',
            'kewajibanPanjang',
            'ekuitas',
            'saldoLancar',
            'saldoPenyertaan',
            'saldoTidakLancar',
            'saldoPendek',
            'saldoPanjang',
            'saldoEkuitas',
            'sumLancar',
            'sumPenyertaan',
            'sumTidakLancar',
            'sumPendek',
            'sumPanjang',
            'sumEkuitas',
            'startDate',
            'endDate',
            'reqStart',
            'reqEnd',
            'sumSHUAkun'
        ));
    }

    public function print_show(Request $request)
    {
        $asetLancar         = Akun::whereIn('kode_akun', [1101, 1102, 1111, 1112, 1121, 1122, 1123, 1124, 1125, 1126, 1127, 1131, 1141])->select('id', 'nama_akun', 'saldo')->get();
        $penyertaan         = Akun::whereIn('kode_akun', [1201, 1202])->select('id', 'nama_akun', 'saldo')->get();
        $asetTidakLancar    = Akun::whereIn('kode_akun', [1301, 1302, 1311, 1321, 1312, 1322, 1313, 1323, 1314, 1324, 1315, 1325, 1316, 1326])->select('id', 'nama_akun', 'saldo')->get();
        $kewajibanPendek    = Akun::whereIn('kode_akun', [2101, 2102, 2111, 2112, 2113, 2114, 2115, 2116, 2117, 2121])->select('id', 'nama_akun', 'saldo')->get();
        $kewajibanPanjang   = Akun::whereIn('kode_akun', [2201])->select('id', 'nama_akun', 'saldo')->get();
        $ekuitas            = Akun::whereIn('kode_akun', [3101, 3102, 3111, 3112, 3113, 3121, 3122])->select('id', 'nama_akun', 'saldo')->get();

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

        $saldoLancar      = array();
        $saldoPenyertaan  = array();
        $saldoTidakLancar = array();
        $saldoPendek      = array();
        $saldoPanjang     = array();
        $saldoEkuitas     = array();

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

        //Kalkulasi
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

        #Sum SHU Akun
        $shuAkunSaldo = Akun::where('kode_akun', 3131)->first();
        $sumSHUAkun = $shuAkunSaldo->saldo - $shuKredit + $shuDebet;

        if ($sumSHUAkun < 0) {
            $sumSHUAkun = $sumSHUAkun * -1;
        }

        #Aset Lancar
        for ($i = 0; $i < sizeof($asetLancar); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $asetLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $asetLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $asetLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $asetLancar[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoLancar, $calculate);
        }

        #Penyertaan
        for ($i = 0; $i < sizeof($penyertaan); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $penyertaan[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $penyertaan[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $penyertaan[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $penyertaan[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $penyertaan[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoPenyertaan, $calculate);
        }

        #Aset Tidak Lancar
        for ($i = 0; $i < sizeof($asetTidakLancar); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $asetTidakLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $asetTidakLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $asetTidakLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetTidakLancar[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $asetTidakLancar[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoTidakLancar, $calculate);
        }

        #Kewajiban Pendek
        for ($i = 0; $i < sizeof($kewajibanPendek); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $kewajibanPendek[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $kewajibanPendek[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $kewajibanPendek[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPendek[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $kewajibanPendek[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoPendek, $calculate);
        }

        #Kewajiban Panjang            
        for ($i = 0; $i < sizeof($kewajibanPanjang); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $kewajibanPanjang[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $kewajibanPanjang[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $kewajibanPanjang[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPanjang[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $kewajibanPanjang[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoPanjang, $calculate);
        }

        #Ekuitas
        for ($i = 0; $i < sizeof($ekuitas); $i++) {
            $a      = JurnalUmumModel::selectRaw('debit as debet, kredit')->where('id_akun', $ekuitas[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b      = JurnalModel::selectRaw('debit as debet, kredit')->where('id_akun', $ekuitas[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jur    = JurnalUmum::selectRaw('debet, kredit')->unionAll($a)->unionAll($b)->where('id_akun', $ekuitas[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->get();
            // $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $ekuitas[$i]->id)->whereBetween('tanggal', [$reqStart, $reqEnd])->first();

            $calculate = $ekuitas[$i]->saldo - $jur->sum('kredit') + $jur->sum('debet');

            array_push($saldoEkuitas, $calculate);
        }

        $sumLancar      = array_sum($saldoLancar);
        $sumPenyertaan  = array_sum($saldoPenyertaan);
        $sumTidakLancar = array_sum($saldoTidakLancar);
        $sumPendek      = array_sum($saldoPendek);
        $sumPanjang     = array_sum($saldoPanjang);
        $sumEkuitas     = array_sum($saldoEkuitas);

        return view('Simpan_Pinjam.laporan.keuangan.print-show', compact(
            'asetLancar',
            'penyertaan',
            'asetTidakLancar',
            'kewajibanPendek',
            'kewajibanPanjang',
            'ekuitas',
            'saldoLancar',
            'saldoPenyertaan',
            'saldoTidakLancar',
            'saldoPendek',
            'saldoPanjang',
            'saldoEkuitas',
            'sumLancar',
            'sumPenyertaan',
            'sumTidakLancar',
            'sumPendek',
            'sumPanjang',
            'sumEkuitas',
            'startDate',
            'endDate',
            'reqStart',
            'reqEnd',
            'sumSHUAkun'
        ));
    }
}
