<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        $data = Anggota::where('username', $request->username)->first();

        if ($data != null) {
            if (Hash::check($request->password, $data->password)) {
                $data['foto'] = asset('storage/foto/' . $data->foto);

                return ResponseFormatter::success($data, 'Berhasil login');
            } else {
                return ResponseFormatter::error('Password tidak sesuai');
            }
        }

        return ResponseFormatter::error('Anda belum terdaftar');
    }
}
