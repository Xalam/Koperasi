<?php

namespace App\Models\Toko\Transaksi\Penjualan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanBarangModel extends Model
{
    use HasFactory;

    protected $table = 'penjualan_barang';

    protected $fillable = [
        'nomor',
        'id_barang',
        'jumlah',
        'total_harga'
    ];
}
