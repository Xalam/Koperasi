<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Akun\AkunModel;
use Illuminate\Http\Request;

class DataAkunController extends Controller
{
    public function dataAkun($id) {
        return AkunModel::where('id', $id)
                            ->orderBy('nama')
                            ->get();
    }
}
