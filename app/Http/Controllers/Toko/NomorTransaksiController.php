<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Transaksi\Hutang\HutangDetailModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use Illuminate\Http\Request;

class NomorTransaksiController extends Controller
{
    public function nomorPembelian($tanggal) {
        $last_nomor = PembelianModel::all();
        $nomor = "B" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);

        return $nomor;
    }

    public function nomorPenjualan($tanggal) {
        $last_nomor = PenjualanModel::all();
        $nomor = "J" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);

        return $nomor;
    }

    public function nomorAngsuran($tanggal) {
        $last_nomor = HutangDetailModel::all();
        $nomor = "A" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);

        return $nomor;
    }
}
