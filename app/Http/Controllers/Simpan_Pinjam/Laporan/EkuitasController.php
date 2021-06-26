<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
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

        #SHU
        $idSimpanPinjam = Akun::where('kode_akun', 4101)->first();
        $idUnitToko     = Akun::where('kode_akun', 4102)->first();
        $idFotoCopy     = Akun::where('kode_akun', 4103)->first();
        $idRuko         = Akun::where('kode_akun', 4104)->first();
        $idArisan       = Akun::where('kode_akun', 4104)->first();
        $idHPP          = Akun::where('kode_akun', 5101)->first();

        $saldoAkhir = array();
        
        $totalSaldo = $idPokok->saldo + $idWajib->saldo + $idInkoppol->saldo + $idDinas->saldo +
                      $idSimsus->saldo + $idCadangan->saldo + $idResiko->saldo + $idSHU->saldo;

        $akun = array(
            $idPokok, $idWajib, $idInkoppol, $idDinas, $idSimsus, $idCadangan, $idResiko, $idSHU
        );

        $akunSaldo = array($idPokok->saldo * -1, $idWajib->saldo * -1, $idInkoppol->saldo * -1, $idDinas->saldo * -1, $idSimsus->saldo * -1, $idCadangan->saldo * -1, $idResiko->saldo * -1, $idSHU->saldo * -1, $totalSaldo * -1);

        if ($request->start_date == '' && $request->end_date == '') {
            // $jurnal = Akun::selectRaw('tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
            //     $join->on('tb_akun.id', 'tb_jurnal.id_akun'); 
            // })
            // ->whereIn('tb_akun.id', [$idPokok->id, $idWajib->id, $idInkoppol->id, $idDinas->id, $idSimsus->id, $idCadangan->id, $idResiko->id])
            // ->groupBy('tb_akun.id')->get();
            
            $pokok      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idPokok->id)->first();
            $wajib      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idWajib->id)->first();
            $inkoppol   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idInkoppol->id)->first();
            $dinas      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idDinas->id)->first();
            $simsus     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idSimsus->id)->first();
            $cadangan   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idCadangan->id)->first();
            $resiko     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idResiko->id)->first();

            $shu = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
                $join->on('tb_akun.id', 'tb_jurnal.id_akun'); 
            })
            ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
            ->groupBy('tb_akun.id')
            ->get();

            $shuKredit = $shu->sum('kredit');
            $shuDebet = $shu->sum('debet');

            $totalPenambahan   = $pokok->kredit + $wajib->kredit + $inkoppol->kredit + $dinas->kredit +
                                  $simsus->kredit + $cadangan->kredit + $resiko->kredit + $shuKredit;
            $totalPengurangan  = $pokok->debet + $wajib->debet + $inkoppol->debet + $dinas->debet +
                                  $simsus->debet + $cadangan->debet + $resiko->debet + $shuDebet;

            $penambahan   = array($pokok->kredit, $wajib->kredit, $inkoppol->kredit, $dinas->kredit, $simsus->kredit, $cadangan->kredit, $resiko->kredit, $shuKredit, $totalPenambahan);
            $pengurangan  = array($pokok->debet, $wajib->debet, $inkoppol->debet, $dinas->debet, $simsus->debet, $cadangan->debet, $resiko->debet, $shuDebet, $totalPengurangan);

            for ($i = 0; $i < sizeof($akunSaldo); $i++) {
                array_push($saldoAkhir, $akunSaldo[$i] - $pengurangan[$i] + $penambahan[$i]);
            }

            return view('Simpan_Pinjam.laporan.ekuitas.show', compact('akun', 'pengurangan', 'penambahan', 'totalSaldo', 'totalPengurangan', 'totalPenambahan', 'saldoAkhir'));

        } else {
            $reqStart   = $request->start_date;
            $reqEnd     = $request->end_date;
            $startDate  = date('Y-m-d', strtotime($reqStart));
            $endDate    = date('Y-m-d', strtotime($reqEnd));

            $pokok      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idPokok->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $wajib      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idWajib->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $inkoppol   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idInkoppol->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $dinas      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idDinas->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $simsus     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idSimsus->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $cadangan   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idCadangan->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $resiko     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idResiko->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

            $shu = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
                $join->on('tb_akun.id', 'tb_jurnal.id_akun'); 
            })
            ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('tb_akun.id')
            ->get();

            $shuKredit = $shu->sum('kredit');
            $shuDebet = $shu->sum('debet');

            $totalPenambahan   = $pokok->kredit + $wajib->kredit + $inkoppol->kredit + $dinas->kredit +
                                  $simsus->kredit + $cadangan->kredit + $resiko->kredit + $shuKredit;
            $totalPengurangan  = $pokok->debet + $wajib->debet + $inkoppol->debet + $dinas->debet +
                                  $simsus->debet + $cadangan->debet + $resiko->debet + $shuDebet;

            $penambahan   = array($pokok->kredit, $wajib->kredit, $inkoppol->kredit, $dinas->kredit, $simsus->kredit, $cadangan->kredit, $resiko->kredit, $shuKredit, $totalPenambahan);
            $pengurangan  = array($pokok->debet, $wajib->debet, $inkoppol->debet, $dinas->debet, $simsus->debet, $cadangan->debet, $resiko->debet, $shuDebet, $totalPengurangan);

            for ($i = 0; $i < sizeof($akunSaldo); $i++) {
                array_push($saldoAkhir, $akunSaldo[$i] - $pengurangan[$i] + $penambahan[$i]);
            }

            return view('Simpan_Pinjam.laporan.ekuitas.show', compact('akun', 'pengurangan', 'penambahan', 'totalSaldo', 'totalPengurangan', 'totalPenambahan', 'saldoAkhir', 'reqStart', 'reqEnd'));
        }
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

        #SHU
        $idSimpanPinjam = Akun::where('kode_akun', 4101)->first();
        $idUnitToko     = Akun::where('kode_akun', 4102)->first();
        $idFotoCopy     = Akun::where('kode_akun', 4103)->first();
        $idRuko         = Akun::where('kode_akun', 4104)->first();
        $idArisan       = Akun::where('kode_akun', 4104)->first();
        $idHPP          = Akun::where('kode_akun', 5101)->first();

        $saldoAkhir = array();
        
        $totalSaldo = $idPokok->saldo + $idWajib->saldo + $idInkoppol->saldo + $idDinas->saldo +
                      $idSimsus->saldo + $idCadangan->saldo + $idResiko->saldo + $idSHU->saldo;

        $akun = array(
            $idPokok, $idWajib, $idInkoppol, $idDinas, $idSimsus, $idCadangan, $idResiko, $idSHU
        );

        $akunSaldo = array($idPokok->saldo * -1, $idWajib->saldo * -1, $idInkoppol->saldo * -1, $idDinas->saldo, $idSimsus->saldo * -1, $idCadangan->saldo * -1, $idResiko->saldo * -1, $idSHU->saldo * -1, $totalSaldo * -1);

        $reqStart   = $request->start_date;
        $reqEnd     = $request->end_date;

        if ($request->start_date == '' && $request->end_date == '') {
            
            $pokok      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idPokok->id)->first();
            $wajib      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idWajib->id)->first();
            $inkoppol   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idInkoppol->id)->first();
            $dinas      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idDinas->id)->first();
            $simsus     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idSimsus->id)->first();
            $cadangan   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idCadangan->id)->first();
            $resiko     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idResiko->id)->first();

            $shu = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
                $join->on('tb_akun.id', 'tb_jurnal.id_akun'); 
            })
            ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
            ->groupBy('tb_akun.id')
            ->get();

            $shuKredit = $shu->sum('kredit');
            $shuDebet = $shu->sum('debet');

            $totalPenambahan   = $pokok->kredit + $wajib->kredit + $inkoppol->kredit + $dinas->kredit +
                                  $simsus->kredit + $cadangan->kredit + $resiko->kredit + $shuKredit;
            $totalPengurangan  = $pokok->debet + $wajib->debet + $inkoppol->debet + $dinas->debet +
                                  $simsus->debet + $cadangan->debet + $resiko->debet + $shuDebet;

            $penambahan   = array($pokok->kredit, $wajib->kredit, $inkoppol->kredit, $dinas->kredit, $simsus->kredit, $cadangan->kredit, $resiko->kredit, $shuKredit, $totalPenambahan);
            $pengurangan  = array($pokok->debet, $wajib->debet, $inkoppol->debet, $dinas->debet, $simsus->debet, $cadangan->debet, $resiko->debet, $shuDebet, $totalPengurangan);

            for ($i = 0; $i < sizeof($akunSaldo); $i++) {
                array_push($saldoAkhir, $akunSaldo[$i] - $pengurangan[$i] + $penambahan[$i]);
            }

            return view('Simpan_Pinjam.laporan.ekuitas.print-show', compact('akun', 'pengurangan', 'penambahan', 'totalSaldo', 'totalPengurangan', 'totalPenambahan', 'saldoAkhir'));

        } else {
            
            $startDate  = date('Y-m-d', strtotime($reqStart));
            $endDate    = date('Y-m-d', strtotime($reqEnd));

            $pokok      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idPokok->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $wajib      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idWajib->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $inkoppol   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idInkoppol->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $dinas      = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idDinas->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $simsus     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idSimsus->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $cadangan   = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idCadangan->id)->whereBetween('tanggal', [$startDate, $endDate])->first();
            $resiko     = JurnalUmum::selectRaw('SUM(debet) as debet, SUM(kredit) as kredit')->where('id_akun', $idResiko->id)->whereBetween('tanggal', [$startDate, $endDate])->first();

            $shu = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
                $join->on('tb_akun.id', 'tb_jurnal.id_akun'); 
            })
            ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('tb_akun.id')
            ->get();

            $shuKredit = $shu->sum('kredit');
            $shuDebet = $shu->sum('debet');

            $totalPenambahan   = $pokok->kredit + $wajib->kredit + $inkoppol->kredit + $dinas->kredit +
                                  $simsus->kredit + $cadangan->kredit + $resiko->kredit + $shuKredit;
            $totalPengurangan  = $pokok->debet + $wajib->debet + $inkoppol->debet + $dinas->debet +
                                  $simsus->debet + $cadangan->debet + $resiko->debet + $shuDebet;

            $penambahan   = array($pokok->kredit, $wajib->kredit, $inkoppol->kredit, $dinas->kredit, $simsus->kredit, $cadangan->kredit, $resiko->kredit, $shuKredit, $totalPenambahan);
            $pengurangan  = array($pokok->debet, $wajib->debet, $inkoppol->debet, $dinas->debet, $simsus->debet, $cadangan->debet, $resiko->debet, $shuDebet, $totalPengurangan);

            for ($i = 0; $i < sizeof($akunSaldo); $i++) {
                array_push($saldoAkhir, $akunSaldo[$i] - $pengurangan[$i] + $penambahan[$i]);
            }

            return view('Simpan_Pinjam.laporan.ekuitas.print-show', compact('akun', 'pengurangan', 'penambahan', 'totalSaldo', 'totalPengurangan', 'totalPenambahan', 'saldoAkhir', 'reqStart', 'reqEnd'));
        }
    }
}
