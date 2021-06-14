<?php

namespace App\Models\Toko\Transaksi\Hutang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HutangDetailModel extends Model
{
    use HasFactory;

    protected $table = 'detail_hutang';

    protected $fillable = [
        'nomor',
        'tanggal',
        'id_hutang',
        'angsuran',
        'sisa_hutang'
    ];
}
