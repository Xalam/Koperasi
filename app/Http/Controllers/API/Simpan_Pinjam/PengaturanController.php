<?php

namespace App\Http\Controllers\API\Simpan_Pinjam;

use App\Http\Controllers\API\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Pengaturan\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $bunga    = Pengaturan::where('id', 1)->first();
        $tenor    = Pengaturan::where('id', 2)->first();
        $provisi  = Pengaturan::where('id', 3)->first();
        $asuransi = Pengaturan::where('id', 4)->first();
        $simWajib = Pengaturan::where('id', 5)->first();

        $expBunga    = explode(" ", $bunga->angka);
        $expTenor    = explode(" ", $tenor->angka);
        $expProvisi  = explode(" ", $provisi->angka);
        $expAsuransi = explode(" ", $asuransi->angka);
        $expSimWajib = explode(" ", $simWajib->angka);

        $bung   = $expBunga[0];
        $prov   = $expProvisi[0];
        $asur   = $expAsuransi[0];
        $wajib  = $expSimWajib[0];

        foreach ($expTenor as $ten) {
            $item[] = array("angka" => $ten);
        }

        $data['bunga']      = $bung;
        $data['tenor']      = $item;
        $data['provisi']    = $prov;
        $data['asuransi']   = $asur;
        $data['wajib']      = $wajib;

        return ResponseFormatter::success($data, 'Berhasil mendapatkan data');
    }
}
