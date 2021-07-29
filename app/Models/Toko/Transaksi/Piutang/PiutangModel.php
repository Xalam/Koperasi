<?php

namespace App\Models\Toko\Transaksi\Piutang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiutangModel extends Model
{
    use HasFactory;

    protected $table = 'piutang';

    protected $fillable = [
        'id_anggota',
        'jumlah_piutang',
        'jumlah_terima_piutang',
        'sisa_piutang',
        'status'
    ];
}
