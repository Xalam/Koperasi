<?php

namespace App\Http\Controllers\Simpan_Pinjam\Master\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login() {
        return view('simpan_pinjam.login');
    }

    public function anggota() {
        
        return view('simpan_pinjam.master.anggota.anggota');
    }
}
