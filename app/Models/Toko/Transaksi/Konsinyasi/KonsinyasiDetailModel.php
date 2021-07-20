<?php

namespace App\Models\Toko\Transaksi\Konsinyasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonsinyasiDetailModel extends Model
{
    use HasFactory;

    protected $table = 'detail_konsinyasi';

    protected $fillable = [
        'nomor',
        'nomor_titip_jual',
        'nomor_jurnal',
        'tanggal',
        'id_konsinyasi',
        'angsuran'
    ];
}
