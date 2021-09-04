<?php

namespace App\Http\Controllers\Simpan_Pinjam\Utils;

use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;
use App\Models\Toko\Transaksi\Jurnal\JurnalModel;
use App\Models\Toko\Transaksi\JurnalUmum\JurnalUmumModel;
use Illuminate\Support\Facades\DB;

class LaporanSHUAkun
{
    public static function show(array $arrayId, $reqStart, $reqEnd)
    {
        $valueAkun = array();

        for ($i = 0; $i < sizeof($arrayId); $i++) {
            $akun   = Akun::findOrFail($arrayId[$i]);
            $a = JurnalUmumModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $arrayId[$i])->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $b = JurnalModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $arrayId[$i])->whereBetween('tanggal', [$reqStart, $reqEnd]);
            $jurnal = JurnalUmum::select(DB::raw("id, CONVERT(kode_jurnal USING utf8) as kode_jurnal, id_akun, tanggal, CONVERT(keterangan USING utf8) as keterangan, debet, kredit"))->union($a)->union($b)->where('id_akun', $arrayId[$i])->whereBetween('tanggal', [$reqStart, $reqEnd])->get();

            $total = $akun->saldo;
            foreach ($jurnal as $key => $value) {
                if ($value->kredit != 0) {
                    $total -= $value->kredit;
                } else {
                    $total += $value->debet;
                }
            }

            array_push($valueAkun, (object)['kode_akun' => $akun->kode_akun, 'nama_akun' => $akun->nama_akun, 'total' => $total]);
        }

        return $valueAkun;
    }

    public static function sum(array $akunValue)
    {
        $sumValue = 0;

        foreach ($akunValue as $key => $value) {
            $sumValue += $value->total;
        }

        return $sumValue;
    }

    public static function dashboard(array $arrayId, $monthly)
    {
        $valueAkun = array();

        for ($i = 0; $i < sizeof($arrayId); $i++) {
            $akun   = Akun::findOrFail($arrayId[$i]);
            $a = JurnalUmumModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $arrayId[$i])->where(DB::raw("DATE_FORMAT(tanggal, '%m')"), $monthly)->whereYear('tanggal', date('Y'));
            $b = JurnalModel::select(DB::raw("id, nomor as kode_jurnal, id_akun, tanggal, keterangan, debit as debet, kredit"))->where('id_akun', $arrayId[$i])->where(DB::raw("DATE_FORMAT(tanggal, '%m')"), $monthly)->whereYear('tanggal', date('Y'));
            $jurnal = JurnalUmum::select(DB::raw("id, CONVERT(kode_jurnal USING utf8) as kode_jurnal, id_akun, tanggal, CONVERT(keterangan USING utf8) as keterangan, debet, kredit"))->union($a)->union($b)->where('id_akun', $arrayId[$i])->where(DB::raw("DATE_FORMAT(tanggal, '%m')"), $monthly)->whereYear('tanggal', date('Y'))->get();

            $total = $akun->saldo;
            foreach ($jurnal as $key => $value) {
                if ($value->kredit != 0) {
                    $total -= $value->kredit;
                } else {
                    $total += $value->debit;
                }
            }

            array_push($valueAkun, (object)['total' => $total]);
        }

        return $valueAkun;
    }
}
