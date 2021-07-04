<?php

namespace App\Models\Toko\Transaksi\TitipJual;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitipJualModel extends Model
{
    use HasFactory;

    protected $table = 'titip_jual';

    protected $fillable = [
        'tanggal',
        'nomor',
        'id_supplier',
        'jumlah_harga',
        'jumlah_bayar',
        'jumlah_kembalian'
    ];
}
