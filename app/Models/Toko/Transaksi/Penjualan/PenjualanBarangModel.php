<?php

namespace App\Models\Toko\Transaksi\Penjualan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanBarangModel extends Model
{
    use HasFactory;

    protected $table = 'detail_jual';

    protected $fillable = [
        'nomor',
        'id_barang',
        'jumlah',
        'total_harga',
        'submited'
    ];
}
