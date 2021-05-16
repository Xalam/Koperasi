<?php

namespace App\Http\Controllers\Simpan_Pinjam\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller 
{
    
    public function index() {
        return view('simpan_pinjam.dashboard.dashboard');
    }
}