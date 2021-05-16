<?php

namespace App\Models\Toko\Transaksi\Pembelian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianModel extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'nomor',
        'id_supplier',
        'id_barang',
        'jumlah',
        'total_harga'
    ];
}
