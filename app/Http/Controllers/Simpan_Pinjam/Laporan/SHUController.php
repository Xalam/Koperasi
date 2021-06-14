<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
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
                
                $data = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
                    $join->on('tb_akun.id', 'tb_jurnal.id_akun'); 
                })
                ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
                ->whereBetween('tb_jurnal.tanggal', [$startDate, $endDate])
                ->groupBy('tb_akun.id')
                ->get();
            } else {
                $data = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
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

            $data = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
                $join->on('tb_akun.id', 'tb_jurnal.id_akun'); 
            })
            ->whereIn('tb_akun.id', [$idSimpanPinjam->id, $idUnitToko->id, $idFotoCopy->id, $idRuko->id, $idArisan->id, $idHPP->id])
            ->whereBetween('tb_jurnal.tanggal', [$reqStart, $reqEnd])
            ->groupBy('tb_akun.id')
            ->get();
        } else {
            $data = Akun::selectRaw('tb_akun.kode_akun, tb_akun.nama_akun, SUM(tb_jurnal.debet) as debet, SUM(tb_jurnal.kredit) as kredit')->leftJoin('tb_jurnal', function($join){
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
}
