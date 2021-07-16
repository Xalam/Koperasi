<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Admin\AdminModel;
use Illuminate\Http\Request;

class KodeAdminController extends Controller
{
    public function kodeAdmin($jabatan) {
        if ($jabatan == "Super_Admin") {
            $initial = "ADM";
        } else if ($jabatan == "Kanit") {
            $initial = "KNT";
        } else if ($jabatan == "Gudang") {
            $initial = "GDG";
        } else {
            $initial = "KSR";
        }

        $last_nomor = AdminModel::where('jabatan', $jabatan)->get();

        if (count($last_nomor) > 0) {
            $kode = $initial . str_pad(count($last_nomor) + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $kode = $initial . str_pad(strval(1), 5, '0', STR_PAD_LEFT);
        }
        
        return $kode;
    }
}
