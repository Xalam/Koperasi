<?php

namespace App\Http\Controllers\Simpan_Pinjam\Utils;

use App\Models\Simpan_Pinjam\Laporan\JurnalUmum;

class SaveJurnalUmum
{
    public static function save($kodeJurnal, $idAkun, $keterangan, $debet, $kredit)
    {
        $jurnal = new JurnalUmum();
        $jurnal->kode_jurnal    = $kodeJurnal;
        $jurnal->id_akun        = $idAkun;
        $jurnal->tanggal        = date('Y-m-d');
        $jurnal->keterangan     = $keterangan;
        $jurnal->debet          = $debet;
        $jurnal->kredit         = $kredit;
        $jurnal->save();
    }
}
