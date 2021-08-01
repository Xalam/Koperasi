<?php

namespace App\Http\Controllers\Simpan_Pinjam\Utils;

use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;

class KodeJurnal
{
    public static function kode()
    {
        $checkJurnal = JurnalUmum::where('kode_jurnal', 'LIKE', 'JU-%')->orderBy('id', 'DESC')->first();

        if ($checkJurnal == null) {
            $idJurnal = 1;
        } else {
            $substrKode = substr($checkJurnal->kode_jurnal, 3);
            $idJurnal   = $substrKode + 1;
        }
        $kodeJurnal = 'JU-' . str_pad($idJurnal, 6, '0', STR_PAD_LEFT);

        return  $kodeJurnal;
    }
}
