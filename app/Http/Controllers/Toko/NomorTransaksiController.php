<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Transaksi\Hutang\HutangDetailModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Konsinyasi\KonsinyasiDetailModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use App\Models\Toko\Transaksi\Piutang\PiutangDetailModel;
use App\Models\Toko\Transaksi\Retur\ReturPembelianModel;
use App\Models\Toko\Transaksi\TitipJual\TitipJualDetailModel;
use Illuminate\Http\Request;

class NomorTransaksiController extends Controller
{
    public function nomorPembelian($tanggal) {
        $last_nomor = PembelianModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "B" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "B" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorReturPembelian($tanggal) {
        $last_nomor = ReturPembelianModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "R" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "R" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorPenjualan($tanggal) {
        $last_nomor = PenjualanModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "J" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "J" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorAngsuran($tanggal) {
        $last_nomor = HutangDetailModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "A" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "A" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }

        return $nomor;
    }

    public function nomorTerimaPiutang($tanggal) {
        $last_nomor = PiutangDetailModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "P" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "P" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorTitipJual($tanggal) {
        $last_nomor = TitipJualDetailModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "T" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "T" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorKonsinyasi($tanggal) {
        $last_nomor = KonsinyasiDetailModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "K" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "K" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }
}
