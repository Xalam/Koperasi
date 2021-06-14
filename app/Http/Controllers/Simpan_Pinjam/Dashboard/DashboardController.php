<?php

namespace App\Http\Controllers\Simpan_Pinjam\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;

class DashboardController extends Controller 
{
    
    public function index() {
        $anggota = Anggota::get();

        $count_anggota = $anggota->count();

        return view('Simpan_Pinjam.dashboard.dashboard')->with([
            'count_anggota' => $count_anggota
        ]);
    }
}