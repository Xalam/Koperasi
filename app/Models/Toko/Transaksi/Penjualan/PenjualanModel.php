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
        'nomor_jurnal',
        'id_anggota',
        'jumlah_harga',
        'jumlah_bayar',
        'jumlah_kembalian',
        'type_penjualan',
        'pembayaran',
        'notified'
    ];
}
