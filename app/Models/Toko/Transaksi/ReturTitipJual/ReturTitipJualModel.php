<?php

namespace App\Models\Toko\Transaksi\ReturTitipJual;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturTitipJualModel extends Model
{
    use HasFactory;

    protected $table = 'retur_titip_jual';

    protected $fillable = [
        'tanggal',
        'nomor',
        'id_titip_jual',
        'id_supplier',
        'jumlah_harga'
    ];
}
