<?php

namespace App\Http\Controllers\Simpan_Pinjam\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Simpan_Pinjam\Pengaturan\Pengaturan;
use App\Models\Toko\Transaksi\Piutang\PiutangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BendaharaController extends Controller
{
    public function index()
    {
        return view('Simpan_Pinjam.laporan.bendahara.index');
    }

    public function show_data(Request $request)
    {
        $tanggal = date('Y-m');

        if ($request->tanggal) {
            $tanggal = $request->tanggal;
        }

        $anggota = Anggota::query()
            ->leftJoin('tb_pinjaman', function ($join) use ($tanggal) {
                $join->on('tb_anggota.id', '=', 'tb_pinjaman.id_anggota')
                    ->where('tb_pinjaman.lunas', 0)
                    ->where('tb_pinjaman.status', 2)
                    ->where(DB::raw("DATE_FORMAT(tb_pinjaman.tanggal, '%Y-%m')"), '<', $tanggal);
            })
            ->leftJoin(DB::raw("(SELECT * FROM (SELECT MAX(id) as maxid FROM piutang GROUP BY id_anggota) t INNER JOIN piutang p ON p.id = t.maxid) as hutang"), function ($join) {
                $join->on('tb_anggota.id', '=', 'hutang.id_anggota');
            })
            ->get([
                'tb_anggota.id',
                'tb_anggota.username',
                'tb_anggota.nama_anggota',
                'tb_pinjaman.nominal_angsuran',
                'hutang.sisa_piutang'
            ]);

        $simpananWajib = Pengaturan::where('id', 5)->first();

        #Jumlah Hutang
        $sumHutang = 0;
        foreach ($anggota as $key => $value) {
            $sumHutang += $value->sisa_piutang;
        }

        #Jumlah Angsuran
        $sumAngsuran = 0;
        foreach ($anggota as $key => $value) {
            $sumAngsuran += $value->nominal_angsuran;
        }

        #Jumlah Simpanan
        $sumSimpanan = 0;
        for ($i = 0; $i < count($anggota); $i++) {
            $sumSimpanan += $simpananWajib->angka;
        }

        #Total Piutang
        $totalPiutang = array();
        foreach ($anggota as $key => $value) {
            array_push($totalPiutang, $value->sisa_piutang + $value->nominal_angsuran + $simpananWajib->angka);
        }

        #Jumlah Total Piutang
        $sumTotalPiutang = array_sum($totalPiutang);

        return view('Simpan_Pinjam.laporan.bendahara.show', compact('anggota', 'simpananWajib', 'sumHutang', 'sumAngsuran', 'sumSimpanan', 'totalPiutang', 'sumTotalPiutang', 'tanggal'));
    }

    public function print_show(Request $request)
    {
        $tanggal = date('Y-m');

        if ($request->tanggal) {
            $tanggal = $request->tanggal;
        }

        $anggota = Anggota::query()
            ->leftJoin('tb_pinjaman', function ($join) use ($tanggal) {
                $join->on('tb_anggota.id', '=', 'tb_pinjaman.id_anggota')
                    ->where('tb_pinjaman.lunas', 0)
                    ->where('tb_pinjaman.status', 2)
                    ->where(DB::raw("DATE_FORMAT(tb_pinjaman.tanggal, '%Y-%m')"), '<', $tanggal);
            })
            ->leftJoin(DB::raw("(SELECT * FROM (SELECT MAX(id) as maxid FROM piutang GROUP BY id_anggota) t INNER JOIN piutang p ON p.id = t.maxid) as hutang"), function ($join) {
                $join->on('tb_anggota.id', '=', 'hutang.id_anggota');
            })
            ->get([
                'tb_anggota.id',
                'tb_anggota.username',
                'tb_anggota.nama_anggota',
                'tb_pinjaman.nominal_angsuran',
                'hutang.sisa_piutang'
            ]);

        $simpananWajib = Pengaturan::where('id', 5)->first();

        #Jumlah Hutang
        $sumHutang = 0;
        foreach ($anggota as $key => $value) {
            $sumHutang += $value->sisa_piutang;
        }

        #Jumlah Angsuran
        $sumAngsuran = 0;
        foreach ($anggota as $key => $value) {
            $sumAngsuran += $value->nominal_angsuran;
        }

        #Jumlah Simpanan
        $sumSimpanan = 0;
        for ($i = 0; $i < count($anggota); $i++) {
            $sumSimpanan += $simpananWajib->angka;
        }

        #Total Piutang
        $totalPiutang = array();
        foreach ($anggota as $key => $value) {
            array_push($totalPiutang, $value->sisa_piutang + $value->nominal_angsuran + $simpananWajib->angka);
        }

        #Jumlah Total Piutang
        $sumTotalPiutang = array_sum($totalPiutang);

        return view('Simpan_Pinjam.laporan.bendahara.print-show', compact('anggota', 'simpananWajib', 'sumHutang', 'sumAngsuran', 'sumSimpanan', 'totalPiutang', 'sumTotalPiutang', 'tanggal'));
    }
}
