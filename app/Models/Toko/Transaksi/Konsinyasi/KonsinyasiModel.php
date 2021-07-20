<?php

namespace App\Models\Toko\Transaksi\Konsinyasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonsinyasiModel extends Model
{
    use HasFactory;

    protected $table = 'konsinyasi';

    protected $fillable = [
        'nomor_titip_jual',
        'id_supplier',
        'jumlah_konsinyasi',
        'jumlah_angsuran',
        'sisa_konsinyasi',
        'status',
        'jatuh_tempo'
    ];
}
