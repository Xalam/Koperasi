<?php

namespace App\Models\Toko\Transaksi\Pembelian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianBarangModel extends Model
{
    use HasFactory;

    protected $table = 'pembelian_barang';

    protected $fillable = [
        'nomor',
        'id_barang',
        'jumlah',
        'total_harga'
    ];
}
