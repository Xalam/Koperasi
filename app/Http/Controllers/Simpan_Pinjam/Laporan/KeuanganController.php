<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
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
        $asetTidakLancar    = Akun::whereIn('kode_akun', [1301, 1302, 1311, 1321, 1312, 1322, 1313, 1323, 1314, 1324, 1315, 1325])->select('id', 'nama_akun', 'saldo')->get();
        $kewajibanPendek    = Akun::whereIn('kode_akun', [2101, 2111, 2112, 2113, 2114, 2115, 2116, 2117, 2121])->select('id', 'nama_akun', 'saldo')->get();
        $kewajibanPanjang   = Akun::whereIn('kode_akun', [2201])->select('id', 'nama_akun', 'saldo')->get();
        $ekuitas            = Akun::whereIn('kode_akun', [3101, 3102, 3111, 3112, 3113, 3121, 3122, 3131])->select('id', 'nama_akun', 'saldo')->get();

        #SHU
        $idSimpanPinjam = Akun::where('kode_akun', 4101)->first();
        $idUnitToko     = Akun::where('kode_akun', 4102)->first();
        $idFotoCopy     = Akun::where('kode_akun', 4103)->first();
        $idRuko         = Akun::where('kode_akun', 4104)->first();
        $idArisan       = Akun::where('kode_akun', 4104)->first();
        $idHPP          = Akun::where('kode_akun', 5101)->first();

        $saldoLancar      = array();
        $saldoPenyertaan  = array();
        $saldoTidakLancar = array();
        $saldoPendek      = array();
        $saldoPanjang     = array();
        $saldoEkuitas     = array();

        if ($request->start_date == '' && $request->end_date == '') {

            #Aset Lancar
            for ($i = 0; $i < sizeof($asetLancar); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetLancar[$i]->id)->first();

                $calculate = $asetLancar[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoLancar, $calculate);
            }

            #Penyertaan
            for ($i = 0; $i < sizeof($penyertaan); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $penyertaan[$i]->id)->first();

                $calculate = $penyertaan[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPenyertaan, $calculate);
            }

            #Aset Tidak Lancar
            for ($i = 0; $i < sizeof($asetTidakLancar); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetTidakLancar[$i]->id)->first();

                $calculate = $asetTidakLancar[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoTidakLancar, $calculate);
            }

            #Kewajiban Pendek
            for ($i = 0; $i < sizeof($kewajibanPendek); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPendek[$i]->id)->first();

                $calculate = $kewajibanPendek[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPendek, $calculate);
            }

            #Kewajiban Panjang            
            for ($i = 0; $i < sizeof($kewajibanPanjang); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPanjang[$i]->id)->first();

                $calculate = $kewajibanPanjang[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPanjang, $calculate);
            }

            #Ekuitas
            for ($i = 0; $i < sizeof($ekuitas); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $ekuitas[$i]->id)->first();

                $calculate = $ekuitas[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoEkuitas, $calculate);
            }

            $sumLancar      = array_sum($saldoLancar);
            $sumPenyertaan  = array_sum($saldoPenyertaan);
            $sumTidakLancar = array_sum($saldoTidakLancar);
            $sumPendek      = array_sum($saldoPendek);
            $sumPanjang     = array_sum($saldoPanjang);
            $sumEkuitas     = array_sum($saldoEkuitas);

            $shu = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
                $join->on('tb_akun.id', 'tb_jurnal.id_akun'); 
            })
            ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
            ->whereYear('tanggal', date('Y'))
            ->groupBy('tb_akun.id')
            ->get();

            $shuTotal = $shu->sum('kredit') - $shu->sum('debet');

            return view('Simpan_Pinjam.laporan.keuangan.show', compact('asetLancar', 'penyertaan', 'asetTidakLancar', 'kewajibanPendek', 'kewajibanPanjang', 'ekuitas', 'saldoLancar', 'saldoPenyertaan', 'saldoTidakLancar',
                        'saldoPendek', 'saldoPanjang', 'saldoEkuitas', 'sumLancar', 'sumPenyertaan', 'sumTidakLancar', 'sumPendek', 'sumPanjang', 'sumEkuitas', 'shuTotal'));
        } else {

            $reqStart   = $request->start_date;
            $reqEnd     = $request->end_date;
            $startDate  = date('Y-m-d', strtotime($reqStart));
            $endDate    = date('Y-m-d', strtotime($reqEnd));

            #Aset Lancar
            for ($i = 0; $i < sizeof($asetLancar); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetLancar[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $asetLancar[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoLancar, $calculate);
            }

            #Penyertaan
            for ($i = 0; $i < sizeof($penyertaan); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $penyertaan[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $penyertaan[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPenyertaan, $calculate);
            }

            #Aset Tidak Lancar
            for ($i = 0; $i < sizeof($asetTidakLancar); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetTidakLancar[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $asetTidakLancar[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoTidakLancar, $calculate);
            }

            #Kewajiban Pendek
            for ($i = 0; $i < sizeof($kewajibanPendek); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPendek[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $kewajibanPendek[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPendek, $calculate);
            }

            #Kewajiban Panjang            
            for ($i = 0; $i < sizeof($kewajibanPanjang); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPanjang[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $kewajibanPanjang[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPanjang, $calculate);
            }

            #Ekuitas
            for ($i = 0; $i < sizeof($ekuitas); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $ekuitas[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $ekuitas[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoEkuitas, $calculate);
            }

            $sumLancar      = array_sum($saldoLancar);
            $sumPenyertaan  = array_sum($saldoPenyertaan);
            $sumTidakLancar = array_sum($saldoTidakLancar);
            $sumPendek      = array_sum($saldoPendek);
            $sumPanjang     = array_sum($saldoPanjang);
            $sumEkuitas     = array_sum($saldoEkuitas);

            $shu = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
                $join->on('tb_akun.id', 'tb_jurnal.id_akun'); 
            })
            ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
            ->whereYear('tanggal', date('Y'))
            ->groupBy('tb_akun.id')
            ->get();

            $shuTotal = $shu->sum('kredit') - $shu->sum('debet');

            return view('Simpan_Pinjam.laporan.keuangan.show', compact('asetLancar', 'penyertaan', 'asetTidakLancar', 'kewajibanPendek', 'kewajibanPanjang', 'ekuitas', 'saldoLancar', 'saldoPenyertaan', 'saldoTidakLancar',
                        'saldoPendek', 'saldoPanjang', 'saldoEkuitas', 'sumLancar', 'sumPenyertaan', 'sumTidakLancar', 'sumPendek', 'sumPanjang', 'sumEkuitas', 'reqStart', 'reqEnd', 'shuTotal'));
        }
    }

    public function print_show(Request $request)
    {
        $asetLancar         = Akun::whereIn('kode_akun', [1101, 1102, 1111, 1112, 1121, 1122, 1123, 1124, 1125, 1126, 1127, 1131, 1141])->select('id', 'nama_akun', 'saldo')->get();
        $penyertaan         = Akun::whereIn('kode_akun', [1201, 1202])->select('id', 'nama_akun', 'saldo')->get();
        $asetTidakLancar    = Akun::whereIn('kode_akun', [1301, 1302, 1311, 1321, 1312, 1322, 1313, 1323, 1314, 1324, 1315, 1325])->select('id', 'nama_akun', 'saldo')->get();
        $kewajibanPendek    = Akun::whereIn('kode_akun', [2101, 2111, 2112, 2113, 2114, 2115, 2116, 2117, 2121])->select('id', 'nama_akun', 'saldo')->get();
        $kewajibanPanjang   = Akun::whereIn('kode_akun', [2201])->select('id', 'nama_akun', 'saldo')->get();
        $ekuitas            = Akun::whereIn('kode_akun', [3101, 3102, 3111, 3112, 3113, 3121, 3122, 3131])->select('id', 'nama_akun', 'saldo')->get();

        #SHU
        $idSimpanPinjam = Akun::where('kode_akun', 4101)->first();
        $idUnitToko     = Akun::where('kode_akun', 4102)->first();
        $idFotoCopy     = Akun::where('kode_akun', 4103)->first();
        $idRuko         = Akun::where('kode_akun', 4104)->first();
        $idArisan       = Akun::where('kode_akun', 4104)->first();
        $idHPP          = Akun::where('kode_akun', 5101)->first();

        $saldoLancar      = array();
        $saldoPenyertaan  = array();
        $saldoTidakLancar = array();
        $saldoPendek      = array();
        $saldoPanjang     = array();
        $saldoEkuitas     = array();

        if ($request->start_date == '' && $request->end_date == '') {

            #Aset Lancar
            for ($i = 0; $i < sizeof($asetLancar); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetLancar[$i]->id)->first();

                $calculate = $asetLancar[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoLancar, $calculate);
            }

            #Penyertaan
            for ($i = 0; $i < sizeof($penyertaan); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $penyertaan[$i]->id)->first();

                $calculate = $penyertaan[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPenyertaan, $calculate);
            }

            #Aset Tidak Lancar
            for ($i = 0; $i < sizeof($asetTidakLancar); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetTidakLancar[$i]->id)->first();

                $calculate = $asetTidakLancar[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoTidakLancar, $calculate);
            }

            #Kewajiban Pendek
            for ($i = 0; $i < sizeof($kewajibanPendek); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPendek[$i]->id)->first();

                $calculate = $kewajibanPendek[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPendek, $calculate);
            }

            #Kewajiban Panjang            
            for ($i = 0; $i < sizeof($kewajibanPanjang); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPanjang[$i]->id)->first();

                $calculate = $kewajibanPanjang[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPanjang, $calculate);
            }

            #Ekuitas
            for ($i = 0; $i < sizeof($ekuitas); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $ekuitas[$i]->id)->first();

                $calculate = $ekuitas[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoEkuitas, $calculate);
            }

            $sumLancar      = array_sum($saldoLancar);
            $sumPenyertaan  = array_sum($saldoPenyertaan);
            $sumTidakLancar = array_sum($saldoTidakLancar);
            $sumPendek      = array_sum($saldoPendek);
            $sumPanjang     = array_sum($saldoPanjang);
            $sumEkuitas     = array_sum($saldoEkuitas);

            $shu = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
                $join->on('tb_akun.id', 'tb_jurnal.id_akun'); 
            })
            ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
            ->whereYear('tanggal', date('Y'))
            ->groupBy('tb_akun.id')
            ->get();

            $shuTotal = $shu->sum('kredit') - $shu->sum('debet');

            return view('Simpan_Pinjam.laporan.keuangan.print-show', compact('asetLancar', 'penyertaan', 'asetTidakLancar', 'kewajibanPendek', 'kewajibanPanjang', 'ekuitas', 'saldoLancar', 'saldoPenyertaan', 'saldoTidakLancar',
                        'saldoPendek', 'saldoPanjang', 'saldoEkuitas', 'sumLancar', 'sumPenyertaan', 'sumTidakLancar', 'sumPendek', 'sumPanjang', 'sumEkuitas', 'shuTotal'));
        } else {

            $reqStart   = $request->start_date;
            $reqEnd     = $request->end_date;
            $startDate  = date('Y-m-d', strtotime($reqStart));
            $endDate    = date('Y-m-d', strtotime($reqEnd));

            #Aset Lancar
            for ($i = 0; $i < sizeof($asetLancar); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetLancar[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $asetLancar[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoLancar, $calculate);
            }

            #Penyertaan
            for ($i = 0; $i < sizeof($penyertaan); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $penyertaan[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $penyertaan[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPenyertaan, $calculate);
            }

            #Aset Tidak Lancar
            for ($i = 0; $i < sizeof($asetTidakLancar); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $asetTidakLancar[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $asetTidakLancar[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoTidakLancar, $calculate);
            }

            #Kewajiban Pendek
            for ($i = 0; $i < sizeof($kewajibanPendek); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPendek[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $kewajibanPendek[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPendek, $calculate);
            }

            #Kewajiban Panjang            
            for ($i = 0; $i < sizeof($kewajibanPanjang); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $kewajibanPanjang[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $kewajibanPanjang[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoPanjang, $calculate);
            }

            #Ekuitas
            for ($i = 0; $i < sizeof($ekuitas); $i++) {
                $jur = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $ekuitas[$i]->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

                $calculate = $ekuitas[$i]->saldo - $jur->kredit + $jur->debet;

                array_push($saldoEkuitas, $calculate);
            }

            $sumLancar      = array_sum($saldoLancar);
            $sumPenyertaan  = array_sum($saldoPenyertaan);
            $sumTidakLancar = array_sum($saldoTidakLancar);
            $sumPendek      = array_sum($saldoPendek);
            $sumPanjang     = array_sum($saldoPanjang);
            $sumEkuitas     = array_sum($saldoEkuitas);

            $shu = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
                $join->on('tb_akun.id', 'tb_jurnal.id_akun'); 
            })
            ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
            ->whereYear('tanggal', date('Y'))
            ->groupBy('tb_akun.id')
            ->get();

            $shuTotal = $shu->sum('kredit') - $shu->sum('debet');

            return view('Simpan_Pinjam.laporan.keuangan.print-show', compact('asetLancar', 'penyertaan', 'asetTidakLancar', 'kewajibanPendek', 'kewajibanPanjang', 'ekuitas', 'saldoLancar', 'saldoPenyertaan', 'saldoTidakLancar',
                        'saldoPendek', 'saldoPanjang', 'saldoEkuitas', 'sumLancar', 'sumPenyertaan', 'sumTidakLancar', 'sumPendek', 'sumPanjang', 'sumEkuitas', 'reqStart', 'reqEnd', 'shuTotal'));
        }
    }
}
