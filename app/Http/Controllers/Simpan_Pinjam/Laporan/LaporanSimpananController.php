<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Simpanan\SaldoTarik;
use App\Models\Simpan_Pinjam\Simpanan\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $a = Simpanan::query()
            ->join('tb_anggota', function ($join) use ($idAnggota) {
                $join->on('tb_anggota.id', '=', 'tb_simpanan.id_anggota')
                    ->where('tb_simpanan.id_anggota', $idAnggota);
            })->where('tb_simpanan.status', 1)->select(DB::raw("
                tb_simpanan.kode_simpanan as kode_simpanan, tb_simpanan.tanggal as tanggal, tb_anggota.kd_anggota as kd_anggota,
                tb_anggota.nama_anggota as nama_anggota, tb_simpanan.jenis_simpanan as jenis_simpanan, tb_simpanan.nominal as nominal
            "));

        $b = SaldoTarik::query()
            ->join('tb_saldo', function ($join) {
                $join->on('tb_saldo.id', '=', 'tb_saldo_tarik.id_saldo');
            })
            ->join('tb_anggota', function ($join) use ($idAnggota) {
                $join->on('tb_saldo.id_anggota', '=', 'tb_anggota.id')
                    ->where('tb_saldo.id_anggota', $idAnggota);
            })->where('tb_saldo_tarik.status', 2)
            ->select(DB::raw("tb_saldo_tarik.id_saldo * 0 as kode_simpanan, tb_saldo_tarik.tanggal as tanggal, tb_anggota.kd_anggota as kd_anggota, 
            tb_anggota.nama_anggota as nama_anggota, tb_saldo.jenis_simpanan as jenis_simpanan, tb_saldo_tarik.nominal * -1 as nominal"));

        $c = $b->union($a);

        $simpananUnion = Simpanan::selectRaw('a.kode_simpanan, a.tanggal, a.kd_anggota, a.nama_anggota, a.jenis_simpanan, a.nominal')
            ->from(DB::raw("(" . $c->toSql() . ") as a"))
            ->mergeBindings($c->getQuery())
            ->orderBy('tanggal')->get();

        if (sizeof($simpanan) == 0) {
            return redirect()->route('lap-simpanan.index')->with([
                'error' => 'Belum terdapat simpanan'
            ]);
        } else {
            return view('Simpan_Pinjam.laporan.simpanan.print', compact('simpananUnion', 'anggota'));
        }
    }

    public function print_all()
    {
        $a = Simpanan::query()
            ->join('tb_anggota', function ($join) {
                $join->on('tb_anggota.id', '=', 'tb_simpanan.id_anggota');
            })->where('tb_simpanan.status', 1)->select(DB::raw("
                tb_simpanan.kode_simpanan as kode_simpanan, tb_simpanan.tanggal as tanggal, tb_anggota.kd_anggota as kd_anggota,
                tb_anggota.nama_anggota as nama_anggota, tb_simpanan.jenis_simpanan as jenis_simpanan, tb_simpanan.nominal as nominal
            "));

        $b = SaldoTarik::query()
            ->join('tb_saldo', function ($join) {
                $join->on('tb_saldo.id', '=', 'tb_saldo_tarik.id_saldo');
            })
            ->join('tb_anggota', function ($join) {
                $join->on('tb_saldo.id_anggota', '=', 'tb_anggota.id');
            })->where('tb_saldo_tarik.status', 2)
            ->select(DB::raw("tb_saldo_tarik.id_saldo * 0 as kode_simpanan, tb_saldo_tarik.tanggal as tanggal, tb_anggota.kd_anggota as kd_anggota, 
            tb_anggota.nama_anggota as nama_anggota, tb_saldo.jenis_simpanan as jenis_simpanan, tb_saldo_tarik.nominal * -1 as nominal"));

        $c = $b->union($a);

        $simpananUnion = Simpanan::selectRaw('a.kode_simpanan, a.tanggal, a.kd_anggota, a.nama_anggota, a.jenis_simpanan, a.nominal')
            ->from(DB::raw("(" . $c->toSql() . ") as a"))
            ->mergeBindings($c->getQuery())
            ->orderBy('tanggal')->get();

        return view('Simpan_Pinjam.laporan.simpanan.print-all', compact('simpananUnion'));
    }

    public function print_show($id)
    {
        $anggota = Anggota::findOrFail($id);

        $a = Simpanan::query()
            ->join('tb_anggota', function ($join) use ($id) {
                $join->on('tb_anggota.id', '=', 'tb_simpanan.id_anggota')
                    ->where('tb_simpanan.id_anggota', $id);
            })->where('tb_simpanan.status', 1)->select(DB::raw("
                tb_simpanan.kode_simpanan as kode_simpanan, tb_simpanan.tanggal as tanggal, tb_anggota.kd_anggota as kd_anggota,
                tb_anggota.nama_anggota as nama_anggota, tb_simpanan.jenis_simpanan as jenis_simpanan, tb_simpanan.nominal as nominal
            "));

        $b = SaldoTarik::query()
            ->join('tb_saldo', function ($join) {
                $join->on('tb_saldo.id', '=', 'tb_saldo_tarik.id_saldo');
            })
            ->join('tb_anggota', function ($join) use ($id) {
                $join->on('tb_saldo.id_anggota', '=', 'tb_anggota.id')
                    ->where('tb_saldo.id_anggota', $id);
            })->where('tb_saldo_tarik.status', 2)
            ->select(DB::raw("tb_saldo_tarik.id_saldo * 0 as kode_simpanan, tb_saldo_tarik.tanggal as tanggal, tb_anggota.kd_anggota as kd_anggota, 
            tb_anggota.nama_anggota as nama_anggota, tb_saldo.jenis_simpanan as jenis_simpanan, tb_saldo_tarik.nominal * -1 as nominal"));

        $c = $b->union($a);

        $simpananUnion = Simpanan::selectRaw('a.kode_simpanan, a.tanggal, a.kd_anggota, a.nama_anggota, a.jenis_simpanan, a.nominal')
            ->from(DB::raw("(" . $c->toSql() . ") as a"))
            ->mergeBindings($c->getQuery())
            ->orderBy('tanggal')->get();

        return view('Simpan_Pinjam.laporan.simpanan.print-show', compact('simpananUnion', 'anggota'));
    }
}
