<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Anggota\AnggotaModel;
use Illuminate\Http\Request;

class DataAnggotaController extends Controller
{
    public function dataAnggota($id) {
        return AnggotaModel::where('id', $id)
                            ->orderBy('nama_anggota')
                            ->get();
    }
}
