<?php

namespace App\Models\Toko\Transaksi\Penjualan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'tanggal',
        'nomor',
        'id_pelanggan',
        'jumlah_harga',
        'jumlah_bayar',
        'jumlah_kembalian',
        'pembayaran'
    ];
}
