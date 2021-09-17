<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Toko\Transaksi\Hutang\HutangDetailModel;
use App\Models\Toko\Transaksi\JurnalUmum\JurnalUmumModel;
use App\Models\Toko\Transaksi\Konsinyasi\KonsinyasiDetailModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use App\Models\Toko\Transaksi\Piutang\PiutangDetailModel;
use App\Models\Toko\Transaksi\Retur\ReturPembelianModel;
use App\Models\Toko\Transaksi\ReturTitipJual\ReturTitipJualBarangModel;
use App\Models\Toko\Transaksi\TitipJual\TitipJualDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function nomorJurnalPenjualan($tanggal, $type) {
        if ($type == 0) {
            $last_nomor = PenjualanModel::all();
    
            if (count($last_nomor) > 0) {
                $nomor = "JJ" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
            } else {
                $nomor = "JJ" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
            }
        } else {
            $nomor_anggota = Anggota::where('id', $type)->first()->kd_anggota;

            $last_nomor = PenjualanModel::select('*')->where('nomor_jurnal', 'LIKE' ,'%JJ' . $nomor_anggota . '%')->get();
    
            if (count($last_nomor) > 0) {
                $nomor = "JJ" . $nomor_anggota . str_pad(strval(count($last_nomor) + 1), 4, '0', STR_PAD_LEFT);
            } else {
                $nomor = "JJ" . $nomor_anggota . str_pad(strval(1), 4, '0', STR_PAD_LEFT);
            }
        }
            
        return $nomor;
    }

    public function nomorJurnalAngsuran($tanggal, $type) {
        $last_nomor = HutangDetailModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "JA" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "JA" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }
        
        return $nomor;
    }

    public function nomorJurnalKonsinyasi($tanggal) {
        $last_nomor = KonsinyasiDetailModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "JK" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "JK" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
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

    public function nomorJurnalReturTitipJual($tanggal) {
        $last_nomor = ReturTitipJualBarangModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "JRT" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "JRT" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
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