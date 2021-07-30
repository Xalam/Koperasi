<?php

namespace App\Http\Controllers\Simpan_Pinjam\Utils;

use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;
use App\Models\Simpan_Pinjam\Master\Akun\Akun;

class LaporanSHUAkun
{
    public static function show(array $arrayId, $reqStart, $reqEnd)
    {
        $valueAkun = array();

        for ($i = 0; $i < sizeof($arrayId); $i++) {
            $akun   = Akun::findOrFail($arrayId[$i]);
            $jurnal = JurnalUmum::where('id_akun', $arrayId[$i])->whereBetween('tanggal', [$reqStart, $reqEnd])->get();

            $total = $akun->saldo;
            foreach ($jurnal as $key => $value) {
                if ($value->kredit != 0) {
                    $total -= $value->kredit;
                } else {
                    $total += $value->debit;
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
}
