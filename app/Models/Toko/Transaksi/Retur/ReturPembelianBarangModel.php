<?php

namespace App\Models\Toko\Transaksi\Retur;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPembelianBarangModel extends Model
{
    use HasFactory;

    protected $table = 'detail_retur';

    protected $fillable = [
        'nomor',
        'nomor_beli',
        'id_barang',
        'harga_beli',
        'jumlah',
        'total_harga'
    ];
}
