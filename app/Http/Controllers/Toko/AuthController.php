<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Admin\AdminModel;
use App\Models\Toko\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index() {
        if (Auth::guard('toko')->check()) {
            return redirect()->to('/toko/dashboard');
        }
        return view('toko.login');
    }

    public function login(Request $request) {
        $data = [
            'nama'     => $request->input('nama'),
            'password'  => $request->input('password'),
            'jabatan'  => $request->input('jabatan'),
        ];
  
        Auth::guard('toko')->attempt($data);
  
        if (Auth::guard('toko')->check()) {
            return redirect()->to('/toko/dashboard');
        } else {
            Session::flash('error', 'LOGIN GAGAL! Silahkan periksa dan coba lagi!');
            return redirect()->route('t-login');
        }
    }
  
    public function register() {
        return view('toko.register');
    }
    
    public function store(Request $request) {
        $create = User::create([
            'kode' => $request->input('kode'),
            'nama' => $request->input('nama'),
            'password' => Hash::make($request->input('password')),
            'jabatan' => $request->input('jabatan')
        ]);

        AdminModel::create($request->all());
  
        if($create){
            Session::flash('success', 'Register berhasil! Silahkan login untuk mengakses data');
            return redirect()->route('login');
        } else {
            Session::flash('errors', ['' => 'Register gagal! Silahkan ulangi beberapa saat lagi']);
            return redirect()->route('register');
        }
    }
  
    public function logout()
    {
        Auth::guard('toko')->logout();
        return redirect()->route('t-login');
    }
}
