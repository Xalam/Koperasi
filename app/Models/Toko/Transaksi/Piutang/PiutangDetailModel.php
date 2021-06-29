<?php

namespace App\Models\Toko\Transaksi\Piutang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiutangDetailModel extends Model
{
    use HasFactory;

    protected $table = 'terima_piutang';

    protected $fillable = [
        'nomor',
        'nomor_jurnal',
        'tanggal',
        'id_piutang',
        'terima_piutang'
    ];
}
