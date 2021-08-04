<?php

namespace App\Models\Toko\Transaksi\PesananOnline;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananOnlineModel extends Model
{
    use HasFactory;

    protected $table = 'pesanan_online';

    protected $fillable = [
        'nomor',
        'id_aggota',
        'daftar_barang',
        'jumlah_harga',
        'proses',
        'pickup'
    ];
}
