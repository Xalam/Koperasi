<?php

namespace App\Http\Controllers\Toko\Master\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko\Master\Admin\AdminModel;

class AdminController extends Controller
{
    public function index() {
        $admin = AdminModel::all();

        return view('toko.master.admin.index', compact('admin'));
    }

    public function store(Request $request) {
        AdminModel::create($request->all());

        return redirect('/toko/master/admin');
    }
}
