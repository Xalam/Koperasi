<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Anggota\AnggotaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataAnggotaController extends Controller
{
    public function dataAnggota($id) {
        return AnggotaModel::leftJoin('piutang', 'piutang.id_anggota', '=', 'tb_anggota.id')
                            ->leftJoin('tb_pinjaman', 'tb_pinjaman.id_anggota', '=', 'tb_anggota.id')
                            ->select('tb_anggota.*', DB::raw('IFNULL(tb_pinjaman.nominal_angsuran, 0) AS angsuran'), DB::raw('IFNULL(tb_pinjaman.lunas, 0) AS status_pinjaman'), 
                                    DB::raw('IFNULL(piutang.sisa_piutang, 0) AS sisa_piutang'))
                            ->where('tb_anggota.id', $id)
                            ->orderBy('tb_anggota.nama_anggota')
                            ->get();
    }
}
