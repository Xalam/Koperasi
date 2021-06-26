<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Pelanggan\PelangganModel;
use Illuminate\Http\Request;

class DataPelangganController extends Controller
{
    public function dataPelanggan($id) {
        return PelangganModel::where('id', $id)
                            ->orderBy('nama')
                            ->get();
    }
}
