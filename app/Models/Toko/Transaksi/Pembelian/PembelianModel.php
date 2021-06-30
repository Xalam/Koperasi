<?php

namespace App\Models\Toko\Transaksi\Pembelian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianModel extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'tanggal',
        'nomor',
        'nomor_jurnal',
        'id_supplier',
        'jumlah_harga',
        'jumlah_bayar',
        'jumlah_kembalian',
        'pembayaran'
    ];
}
