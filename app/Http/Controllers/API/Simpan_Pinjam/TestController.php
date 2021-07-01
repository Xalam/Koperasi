<?php

namespace App\Http\Controllers\API\Simpan_Pinjam;

use App\Http\Controllers\API\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $data = Anggota::all();

        if ($data)
            return ResponseFormatter::success($data, 'berhasil mendapatkan data Anggota');
        else
            return ResponseFormatter::error('gagal mendapatkan data Anggota');
    }
}
