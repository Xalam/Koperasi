<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Simpan_Pinjam\Utils\LaporanSHUAkun;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Pengaturan\PembagianSHU;
use App\Models\Simpan_Pinjam\Pinjaman\Pinjaman;
use App\Models\Simpan_Pinjam\Simpanan\Saldo;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
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

        //Kalkulasi
        $labaKotor = ($sumAkunFour * -1) - $sumAkunFive;
        $totalBeban = ($sumAkunSix + $sumAkunSixThree + $sumAkunSixFour) * -1;
        $pendapatanOperasional = $labaKotor + $totalBeban;

        $sumSHU = $pendapatanOperasional + ($sumAkunFourTwo * -1);
        $pajakSHU = 0.5 / 100 * ($sumAkunFour * -1);
        $sumSHUPajak = $sumSHU - $pajakSHU;

        $calculatePembagian = array();
        for ($i = 0; $i < sizeof($pembagian); $i++) {

            $pembagianId = PembagianSHU::where('id', $pembagian[$i]->id)->first();

            $valuePembagian = ($pembagianId->angka / 100) * $sumSHUPajak;

            array_push($calculatePembagian, $valuePembagian);
        }

        //Jasa Anggota
        $jasaAnggota = array();

        $anggota = Anggota::get();

        for ($i = 0; $i < sizeof($anggota); $i++) {
            $anggotaId      = Anggota::findOrFail($anggota[$i]->id);
            $simWajib       = Saldo::where('id_anggota', $anggotaId->id)->where('jenis_simpanan', 2)->first();
            $simSukarela    = Saldo::where('id_anggota', $anggotaId->id)->where('jenis_simpanan', 3)->first();
            $pinjaman       = Pinjaman::where('id_anggota', $anggotaId->id)->where('status', 2)->get();
            $penjualan      = PenjualanModel::selectRaw('SUM(jumlah_harga) AS total')->where('id_anggota', $anggotaId->id)->get();

            $saldoWajib     = 0;
            $saldoSukarela  = 0;

            #Wajib
            if ($simWajib) {
                $saldoWajib = $simWajib->saldo;
            }

            #Sukarela
            if ($simSukarela) {
                $saldoSukarela = $simSukarela->saldo;
            }

            #Pinjaman
            $totalPinjaman = 0;
            foreach ($pinjaman as $key => $value) {
                $totalPinjaman += $value->nominal_pinjaman;
            }

            #Belanja Toko
            $totalPenjualan = 0;
            if ($penjualan) {
                foreach ($penjualan as $key => $value) {
                    $totalPenjualan = $value->total;
                }
            }

            #Keaktifan Anggota
            $keaktifanAnggota = $saldoWajib + $saldoSukarela + $totalPinjaman + $totalPenjualan;

            #Pembagian SHU
            $persenAnggota = PembagianSHU::where('nama', 'LIKE', '%Anggota%')->first();

            $shu = 0;
            if ($keaktifanAnggota != 0) {
                $shu = ($keaktifanAnggota / ($keaktifanAnggota += $keaktifanAnggota)) * ($persenAnggota->angka / 100 * $sumSHUPajak);
            }

            array_push($jasaAnggota, (object)['username' => $anggotaId->username, 'nama_anggota' => $anggotaId->nama_anggota, 'wajib' => $saldoWajib, 'sukarela' => $saldoSukarela, 'total_pinjaman' => $totalPinjaman, 'total_penjualan' => $totalPenjualan, 'keaktifan_anggota' => $saldoWajib + $saldoSukarela + $totalPinjaman + $totalPenjualan]);
        }

        #Pembagian SHU
        $persenAnggota = PembagianSHU::where('nama', 'LIKE', '%Jasa Anggota%')->first();
        $sumAktifAnggota = 0;
        $pembagianSHU = array();

        foreach ($jasaAnggota as $key => $value) {
            $sumAktifAnggota += $value->keaktifan_anggota;
        }

        for ($i = 0; $i < sizeof($jasaAnggota); $i++) {
            $shu = 0;

            $shu = ($jasaAnggota[$i]->keaktifan_anggota / $sumAktifAnggota) * ($persenAnggota->angka / 100) * $sumSHUPajak;

            array_push($pembagianSHU, $shu);
        }

        #Jumlah
        $jumWajib        = 0;
        $jumSukarela     = 0;
        $jumPinjaman     = 0;
        $jumPenjualan    = 0;
        $jumAktifAnggota = 0;
        $jumPembagianSHU = array_sum($pembagianSHU);

        for ($i = 0; $i < sizeof($jasaAnggota); $i++) {
            $jumWajib += $jasaAnggota[$i]->wajib;
            $jumSukarela += $jasaAnggota[$i]->sukarela;
            $jumPinjaman += $jasaAnggota[$i]->total_pinjaman;
            $jumPenjualan += $jasaAnggota[$i]->total_penjualan;
            $jumAktifAnggota += $jasaAnggota[$i]->keaktifan_anggota;
        }

        return view('Simpan_Pinjam.laporan.shu.print-show', compact(
            'reqStart',
            'reqEnd',
            'startDate',
            'endDate',
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
            'pembagian',
            'sumSHU',
            'pajakSHU',
            'sumSHUPajak',
            'calculatePembagian',
            'jasaAnggota',
            'pembagianSHU',
            'jumWajib',
            'jumSukarela',
            'jumPinjaman',
            'jumPenjualan',
            'jumAktifAnggota',
            'jumPembagianSHU',
            'labaKotor',
            'totalBeban',
            'pendapatanOperasional'
        ));
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

        //Kalkulasi
        $labaKotor = ($sumAkunFour * -1) - $sumAkunFive;
        $totalBeban = ($sumAkunSix + $sumAkunSixThree + $sumAkunSixFour) * -1;
        $pendapatanOperasional = $labaKotor + $totalBeban;

        $sumSHU = $pendapatanOperasional + ($sumAkunFourTwo * -1);
        $pajakSHU = 0.5 / 100 * ($sumAkunFour * -1);
        $sumSHUPajak = $sumSHU - $pajakSHU;

        $calculatePembagian = array();
        for ($i = 0; $i < sizeof($pembagian); $i++) {

            $pembagianId = PembagianSHU::where('id', $pembagian[$i]->id)->first();

            $valuePembagian = ($pembagianId->angka / 100) * $sumSHUPajak;

            array_push($calculatePembagian, $valuePembagian);
        }

        //Jasa Anggota
        $jasaAnggota = array();

        $anggota = Anggota::get();

        for ($i = 0; $i < sizeof($anggota); $i++) {
            $anggotaId      = Anggota::findOrFail($anggota[$i]->id);
            $simWajib       = Saldo::where('id_anggota', $anggotaId->id)->where('jenis_simpanan', 2)->first();
            $simSukarela    = Saldo::where('id_anggota', $anggotaId->id)->where('jenis_simpanan', 3)->first();
            $pinjaman       = Pinjaman::where('id_anggota', $anggotaId->id)->where('status', 2)->get();
            $penjualan      = PenjualanModel::selectRaw('SUM(jumlah_harga) AS total')->where('id_anggota', $anggotaId->id)->get();

            $saldoWajib     = 0;
            $saldoSukarela  = 0;

            #Wajib
            if ($simWajib) {
                $saldoWajib = $simWajib->saldo;
            }

            #Sukarela
            if ($simSukarela) {
                $saldoSukarela = $simSukarela->saldo;
            }

            #Pinjaman
            $totalPinjaman = 0;
            foreach ($pinjaman as $key => $value) {
                $totalPinjaman += $value->nominal_pinjaman;
            }

            #Belanja Toko
            $totalPenjualan = 0;
            if ($penjualan) {
                foreach ($penjualan as $key => $value) {
                    $totalPenjualan = $value->total;
                }
            }

            #Keaktifan Anggota
            $keaktifanAnggota = $saldoWajib + $saldoSukarela + $totalPinjaman + $totalPenjualan;

            #Pembagian SHU
            $persenAnggota = PembagianSHU::where('nama', 'LIKE', '%Jasa Anggota%')->first();

            $shu = 0;
            if ($keaktifanAnggota != 0) {
                $shu = ($keaktifanAnggota / ($keaktifanAnggota += $keaktifanAnggota)) * ($persenAnggota->angka / 100 * $sumSHUPajak);
            }

            array_push($jasaAnggota, (object)['username' => $anggotaId->username, 'nama_anggota' => $anggotaId->nama_anggota, 'wajib' => $saldoWajib, 'sukarela' => $saldoSukarela, 'total_pinjaman' => $totalPinjaman, 'total_penjualan' => $totalPenjualan, 'keaktifan_anggota' => $saldoWajib + $saldoSukarela + $totalPinjaman + $totalPenjualan]);
        }

        #Pembagian SHU
        $persenAnggota = PembagianSHU::where('nama', 'LIKE', '%Jasa Anggota%')->first();
        $sumAktifAnggota = 0;
        $pembagianSHU = array();

        foreach ($jasaAnggota as $key => $value) {
            $sumAktifAnggota += $value->keaktifan_anggota;
        }

        for ($i = 0; $i < sizeof($jasaAnggota); $i++) {
            $shu = 0;

            $shu = ($jasaAnggota[$i]->keaktifan_anggota / $sumAktifAnggota) * ($persenAnggota->angka / 100) * $sumSHUPajak;

            array_push($pembagianSHU, $shu);
        }

        #Jumlah
        $jumWajib        = 0;
        $jumSukarela     = 0;
        $jumPinjaman     = 0;
        $jumPenjualan    = 0;
        $jumAktifAnggota = 0;
        $jumPembagianSHU = array_sum($pembagianSHU);

        for ($i = 0; $i < sizeof($jasaAnggota); $i++) {
            $jumWajib += $jasaAnggota[$i]->wajib;
            $jumSukarela += $jasaAnggota[$i]->sukarela;
            $jumPinjaman += $jasaAnggota[$i]->total_pinjaman;
            $jumPenjualan += $jasaAnggota[$i]->total_penjualan;
            $jumAktifAnggota += $jasaAnggota[$i]->keaktifan_anggota;
        }

        return view('Simpan_Pinjam.laporan.shu.show', compact(
            'reqStart',
            'reqEnd',
            'startDate',
            'endDate',
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
            'pembagian',
            'sumSHU',
            'pajakSHU',
            'sumSHUPajak',
            'calculatePembagian',
            'jasaAnggota',
            'pembagianSHU',
            'jumWajib',
            'jumSukarela',
            'jumPinjaman',
            'jumPenjualan',
            'jumAktifAnggota',
            'jumPembagianSHU',
            'labaKotor',
            'totalBeban',
            'pendapatanOperasional'
        ));
    }
}
