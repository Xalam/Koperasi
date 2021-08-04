<?php

namespace App\Models\Toko\Transaksi\Hutang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HutangModel extends Model
{
    use HasFactory;

    protected $table = 'hutang';

    protected $fillable = [
        'nomor_beli',
        'id_supplier',
        'jumlah_hutang',
        'jumlah_angsuran',
        'sisa_hutang',
        'status',
        'jatuh_tempo',
        'alert_status'
    ];
}
