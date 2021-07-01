<?php

namespace App\Http\Controllers\Toko\Android;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Anggota\AnggotaModel;
use App\Models\Toko\Master\Barang\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AndroidController extends Controller
{
    public function apiAnggota() {
        return AnggotaModel::orderBy('nama_anggota')
                            ->get();
    }

    public function apiBarang() {
        return BarangModel::orderBy('nama')
                            ->get();
    }

    public function apiLogin(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = AnggotaModel::where('username', $username)->first();
        
        if ($user && Hash::check($password, $user->password)) {
            return "Success";
        } else {
            return "Failed";
        }
    }
}
