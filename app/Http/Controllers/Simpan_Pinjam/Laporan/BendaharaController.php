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
            $tanggal = date('Y-m', strtotime($request->tanggal));
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
            ->orderBy('tb_anggota.id', 'ASC')
            ->get([
                'tb_anggota.id',
                'tb_anggota.username',
                'tb_anggota.nama_anggota',
                'tb_anggota.created_at',
                'tb_pinjaman.nominal_angsuran',
                'hutang.sisa_piutang'
            ]);

        $simpanan = Pengaturan::where('id', 5)->first();
        $expBunga = explode(" ", $simpanan->angka);
        $simpananWajib = $expBunga[0];

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
            if (date('Y-m', strtotime($anggota[$i]->created_at)) < $tanggal) {
                $simWajib = $expBunga[0];
            } else {
                $simWajib = 0;
            }
            $sumSimpanan += $simWajib;
        }

        #Total Piutang
        $totalPiutang = array();
        foreach ($anggota as $key => $value) {
            if (date('Y-m', strtotime($value->created_at)) < $tanggal) {
                $simWajibPiutang = $expBunga[0];
            } else {
                $simWajibPiutang = 0;
            }
            array_push($totalPiutang, $value->sisa_piutang + $value->nominal_angsuran + $simWajibPiutang);
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
            ->orderBy('tb_anggota.id', 'ASC')
            ->get([
                'tb_anggota.id',
                'tb_anggota.username',
                'tb_anggota.nama_anggota',
                'tb_anggota.created_at',
                'tb_pinjaman.nominal_angsuran',
                'hutang.sisa_piutang'
            ]);

        $simpanan = Pengaturan::where('id', 5)->first();
        $expBunga = explode(" ", $simpanan->angka);
        $simpananWajib = $expBunga[0];

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
            if (date('Y-m', strtotime($anggota[$i]->created_at)) < $tanggal) {
                $simWajib = $expBunga[0];
            } else {
                $simWajib = 0;
            }
            $sumSimpanan += $simWajib;
        }

        #Total Piutang
        $totalPiutang = array();
        foreach ($anggota as $key => $value) {
            if (date('Y-m', strtotime($value->created_at)) < $tanggal) {
                $simWajibPiutang = $expBunga[0];
            } else {
                $simWajibPiutang = 0;
            }
            array_push($totalPiutang, $value->sisa_piutang + $value->nominal_angsuran + $simWajibPiutang);
        }

        #Jumlah Total Piutang
        $sumTotalPiutang = array_sum($totalPiutang);

        return view('Simpan_Pinjam.laporan.bendahara.print-show', compact('anggota', 'simpananWajib', 'sumHutang', 'sumAngsuran', 'sumSimpanan', 'totalPiutang', 'sumTotalPiutang', 'tanggal'));
    }
}
