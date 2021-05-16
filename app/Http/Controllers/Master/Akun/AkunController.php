<?php

namespace App\Http\Controllers\Master\Akun;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Akun\AkunModel;

class AkunController extends Controller
{
    public function index() {
        $akun = AkunModel::all();

        return view('master.akun.index', compact('akun'));
    }

    public function store(Request $request) {
        AkunModel::create($request->all());

        return redirect('/master/akun');
    }
}
