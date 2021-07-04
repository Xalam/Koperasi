<?php

namespace App\Http\Controllers\Simpan_Pinjam\Auth;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login()
    {
        return view('Simpan_Pinjam.login');
    }

    public function post_login(Request $request)
    {
        $check_user = User::where('username', $request->username)->first();

        // if ($check_user && Hash::check($request->password, $check_user->password)) {
        //     return redirect()->route('dashboard');
        // }

        if (Auth::guard('simpan-pinjam')->attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect()->route('dashboard')->with([
                'success' => 'Selamat datang ' . $check_user->name . '!'
            ]);
        }

        return redirect()->route('s-login')->with([
            'error' => 'Harap masukkan username dan password yang sesuai'
        ]);
    }

    public function logout()
    {
        Auth::guard('simpan-pinjam')->logout();

        return redirect()->route('s-login');
    }
}
