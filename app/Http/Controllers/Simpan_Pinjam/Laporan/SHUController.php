<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Simpan_Pinjam\Utils\LaporanSHUAkun;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Simpan_Pinjam\Pengaturan\PembagianSHU;
use Illuminate\Http\Request;

class SHUController extends Controller
{
    public function index()
    {
        return view('Simpan_Pinjam.laporan.shu.index');
    }

    public function show_data(Request $request)
    {
        if (request()->ajax()) {

            $idSimpanPinjam = Akun::where('kode_akun', 4101)->first();
            $idUnitToko     = Akun::where('kode_akun', 4102)->first();
            $idFotoCopy     = Akun::where('kode_akun', 4103)->first();
            $idRuko         = Akun::where('kode_akun', 4104)->first();
            $idArisan       = Akun::where('kode_akun', 4104)->first();
            $idHPP          = Akun::where('kode_akun', 5101)->first();

            $startDate = date('Y-m-d', strtotime($request->start_date));
            $endDate = date('Y-m-d', strtotime($request->end_date));

            if ($request->start_date != '' && $request->end_date != '') {

                $data = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function ($join) {
                    $join->on('tb_akun.id', 'tb_jurnal.id_akun');
                })
                    ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
                    ->whereBetween('tb_jurnal.tanggal', [$startDate, $endDate])
                    ->groupBy('tb_akun.id')
                    ->get();
            } else {
                $data = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function ($join) {
                    $join->on('tb_akun.id', 'tb_jurnal.id_akun');
                })
                    ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
                    ->groupBy('tb_akun.id')->get();
            }

            $json['data'] = $data;
            $json['total_kredit'] = $data->sum('kredit');
            $json['total_debet']  = $data->sum('debet');
            $json['laba']         = $data->sum('kredit') - $data->sum('debet');

            echo json_encode($json);
        }
    }

    public function print_show(Request $request)
    {
        $idSimpanPinjam = Akun::where('kode_akun', 4101)->first();
        $idUnitToko     = Akun::where('kode_akun', 4102)->first();
        $idFotoCopy     = Akun::where('kode_akun', 4103)->first();
        $idRuko         = Akun::where('kode_akun', 4104)->first();
        $idArisan       = Akun::where('kode_akun', 4104)->first();
        $idHPP          = Akun::where('kode_akun', 5101)->first();

        $startDate = '';
        $endDate   = date('d-m-Y');

        if ($request->start_date != '' && $request->end_date != '') {

            $reqStart = date('Y-m-d', strtotime($request->start_date));
            $reqEnd = date('Y-m-d', strtotime($request->end_date));

            $startDate = date('d-m-Y', strtotime($request->start_date));
            $endDate = date('d-m-Y', strtotime($request->end_date));

            $data = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function ($join) {
                $join->on('tb_akun.id', 'tb_jurnal.id_akun');
            })
                ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
                ->whereBetween('tb_jurnal.tanggal', [$reqStart, $reqEnd])
                ->groupBy('tb_akun.id')
                ->get();
        } else {
            $data = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function ($join) {
                $join->on('tb_akun.id', 'tb_jurnal.id_akun');
            })
                ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
                ->groupBy('tb_akun.id')->get();
        }

        $total_kredit = $data->sum('kredit');
        $total_debet  = $data->sum('debet');
        $laba         = $data->sum('kredit') - $data->sum('debet');

        return view('Simpan_Pinjam.laporan.shu.print-show', compact('data', 'total_kredit', 'total_debet', 'laba', 'startDate', 'endDate'));
    }

    public function show(Request $request)
    {
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

        $valueAkunSixFour = LaporanSHUAkun::show($akunSixFour, $reqStart, $reqEnd);
        $sumAkunSixFour    = LaporanSHUAkun::sum($valueAkunSixFour);

        #Kode Akun 42XX
        $akunFourTwo = array(
            $idPendukungSHU->id,
            $idPendukungBunga->id,
            $idPendukungProvisi->id
        );

        $valueAkunFourTwo  = LaporanSHUAkun::show($akunFourTwo, $reqStart, $reqEnd);
        $sumAkunFourTwo    = LaporanSHUAkun::sum($valueAkunFourTwo);


        //PEMBAGIAN
        $pembagian = PembagianSHU::get();

        return view('Simpan_Pinjam.laporan.shu.show', compact(
            'valueAkunFour',
            'valueAkunFive',
            'valueAkunSix',
            'valueAkunSixThree',
            'valueAkunSixFour',
            'valueAkunFourTwo',
            'sumAkunFour',
            'sumAkunFive',
            'sumAkunSix',
            'sumAkunSixThree',
            'sumAkunSixFour',
            'sumAkunFourTwo',
            'pembagian'
        ));
    }
}
