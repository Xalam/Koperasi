<?php

namespace App\Models\Toko\Transaksi\Retur;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPembelianModel extends Model
{
    use HasFactory;

    protected $table = 'retur';

    protected $fillable = [
        'tanggal',
        'nomor',
        'id_beli',
        'id_supplier',
        'jumlah_harga'
    ];
}
