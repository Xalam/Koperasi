<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Toko\Transaksi\Hutang\HutangDetailModel;
use App\Models\Toko\Transaksi\JurnalUmum\JurnalUmumModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use App\Models\Toko\Transaksi\Piutang\PiutangDetailModel;
use App\Models\Toko\Transaksi\Retur\ReturPembelianModel;
use App\Models\Toko\Transaksi\TitipJual\TitipJualDetailModel;
use Illuminate\Http\Request;

class NomorJurnalController extends Controller
{
    public function nomorJurnalPembelian($tanggal) {
        $last_nomor = PembelianModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "JB" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "JB" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorJurnalReturPembelian($tanggal) {
        $last_nomor = ReturPembelianModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "JR" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "JR" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorJurnalPenjualan($tanggal) {
        $last_nomor = PenjualanModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "JJ" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "JJ" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorJurnalAngsuran($tanggal) {
        $last_nomor = HutangDetailModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "JA" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "JA" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorJurnalTerimaPiutang($tanggal) {
        $last_nomor = PiutangDetailModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "JP" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "JP" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorJurnalTitipJual($tanggal) {
        $last_nomor = TitipJualDetailModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "JT" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "JT" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorJurnalUmum($tanggal) {
        $last_nomor = JurnalUmumModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "JU" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "JU" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }
}