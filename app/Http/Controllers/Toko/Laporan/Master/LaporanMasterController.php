<?php

namespace App\Http\Controllers\Toko\Laporan\Master;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Admin\AdminModel;
use App\Models\Toko\Master\Barang\BarangModel;
use App\Models\Toko\Master\Pelanggan\PelangganModel;
use App\Models\Toko\Master\Supplier\SupplierModel;
use Illuminate\Http\Request;

class LaporanMasterController extends Controller
{
    public function index(Request $request) {
        $bagian = $request->input('bagian');

        if ($bagian) {
            if ($bagian == "Admin") {
                $laporan_master = AdminModel::paginate(10);
            } else if ($bagian == "Barang") {
                $laporan_master = BarangModel::paginate(10);
            } else if ($bagian == "Pelanggan") {
                $laporan_master = PelangganModel::paginate(10);
            } else {
                $laporan_master = SupplierModel::paginate(10);
            }

            return view ('toko.laporan.master.index', compact('bagian', 'laporan_master'));
        } else {
            return view ('toko.laporan.master.index');
        }
    }
}
