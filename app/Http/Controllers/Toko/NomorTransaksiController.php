<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Simpan_Pinjam\Master\Anggota\Anggota;
use App\Models\Toko\Transaksi\Hutang\HutangDetailModel;
use App\Models\Toko\Transaksi\Hutang\HutangModel;
use App\Models\Toko\Transaksi\Konsinyasi\KonsinyasiDetailModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianBarangModel;
use App\Models\Toko\Transaksi\Pembelian\PembelianModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanBarangModel;
use App\Models\Toko\Transaksi\Penjualan\PenjualanModel;
use App\Models\Toko\Transaksi\Piutang\PiutangDetailModel;
use App\Models\Toko\Transaksi\Retur\ReturPembelianBarangModel;
use App\Models\Toko\Transaksi\Retur\ReturPembelianModel;
use App\Models\Toko\Transaksi\ReturTitipJual\ReturTitipJualBarangModel;
use App\Models\Toko\Transaksi\ReturTitipJual\ReturTitipJualModel;
use App\Models\Toko\Transaksi\TitipJual\TitipJualDetailModel;
use App\Models\Toko\Transaksi\TitipJual\TitipJualModel;
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

        PembelianModel::where('nomor', $nomor)->delete();
        PembelianBarangModel::where('nomor', $nomor)->delete();
        
        return $nomor;
    }

    public function nomorReturPembelian($tanggal) {
        $last_nomor = ReturPembelianModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "R" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "R" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }

        ReturPembelianModel::where('nomor', $nomor)->delete();
        ReturPembelianBarangModel::where('nomor', $nomor)->delete();
        
        return $nomor;
    }

    public function nomorPenjualan($tanggal, $type) {
        if ($type == 0) {
            $last_nomor = PenjualanModel::all();
    
            if (count($last_nomor) > 0) {
                $nomor = "J" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
            } else {
                $nomor = "J" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
            }
        } else {
            $nomor_anggota = Anggota::where('id', $type)->first()->kd_anggota;

            $last_nomor = PenjualanModel::select('*')->where('nomor_jurnal', 'LIKE' ,'%J' . $nomor_anggota . '%')->get();
    
            if (count($last_nomor) > 0) {
                $nomor = "J" . $nomor_anggota . str_pad(strval(count($last_nomor) + 1), 4, '0', STR_PAD_LEFT);
            } else {
                $nomor = "J" . $nomor_anggota . str_pad(strval(1), 4, '0', STR_PAD_LEFT);
            }
        }

        PenjualanModel::where('nomor', $nomor)->delete();
        PenjualanBarangModel::where('nomor', $nomor)->delete();
        
        return $nomor;
    }

    public function nomorAngsuran($tanggal, $type) {
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

        TitipJualModel::where('nomor', $nomor)->delete();
        TitipJualDetailModel::where('nomor', $nomor)->delete();
        
        return $nomor;
    }

    public function nomorReturTitipJual($tanggal) {
        $last_nomor = ReturTitipJualBarangModel::all();

        if (count($last_nomor) > 0) {
            $nomor = "RT" . $tanggal . str_pad(strval($last_nomor[count($last_nomor) - 1]->id + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $nomor = "RT" . $tanggal . str_pad(strval(1), 6, '0', STR_PAD_LEFT);
        }

        ReturTitipJualModel::where('nomor', $nomor)->delete();
        ReturTitipJualBarangModel::where('nomor', $nomor)->delete();
        
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
