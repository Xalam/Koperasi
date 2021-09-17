<?php

namespace App\Models\Toko\Transaksi\ReturTitipJual;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturTitipJualBarangModel extends Model
{
    use HasFactory;

    protected $table = 'detail_retur_titip_jual';

    protected $fillable = [
        'nomor',
        'nomor_titip_jual',
        'id_barang',
        'harga_beli',
        'jumlah',
        'total_harga'
    ];
}
