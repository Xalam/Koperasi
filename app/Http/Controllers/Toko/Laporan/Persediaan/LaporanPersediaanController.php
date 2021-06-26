<?php

namespace App\Http\Controllers\Toko\Laporan\Persediaan;

use App\Http\Controllers\Controller;
use App\Models\Toko\Master\Barang\BarangModel;
use Illuminate\Http\Request;

class LaporanPersediaanController extends Controller
{
    public function index(Request $request) {
        $jumlah_barang = $request->input('jumlah_barang');

        if ($jumlah_barang) {
            $laporan_persediaan = BarangModel::where('stok', '<', $jumlah_barang)->paginate(10);

            return view ('toko.laporan.persediaan.index', compact('laporan_persediaan', 'jumlah_barang'));
        }

        return view ('toko.laporan.persediaan.index');
    }
}
